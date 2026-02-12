<?php

declare(strict_types=1);

namespace Ciencia\Validators;

use Ciencia\DomainObjects\Enums\QuestionBelongsTo;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\Generated\ProductDomainObjectAbstract;
use Ciencia\DomainObjects\Generated\QuestionDomainObjectAbstract;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\DomainObjects\QuestionDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\EventSettingsRepositoryInterface;
use Ciencia\Repository\Interfaces\ProductRepositoryInterface;
use Ciencia\Repository\Interfaces\QuestionRepositoryInterface;
use Ciencia\Validators\Rules\OrderQuestionRule;
use Ciencia\Validators\Rules\ProductQuestionRule;
use Illuminate\Routing\Route;

class CompleteOrderValidator extends BaseValidator
{
    public function __construct(
        private readonly QuestionRepositoryInterface      $questionRepository,
        private readonly ProductRepositoryInterface       $productRepository,
        private readonly EventSettingsRepositoryInterface $eventSettingsRepository,
        private readonly Route                            $route
    )
    {
    }

    public function rules(): array
    {
        $questions = $this->questionRepository
            ->loadRelation(
                new Relationship(ProductDomainObject::class, [
                    new Relationship(ProductPriceDomainObject::class)
                ])
            )
            ->findWhere(
                [QuestionDomainObjectAbstract::EVENT_ID => $this->route->parameter('event_id')]
            );

        $orderQuestions = $questions->filter(
            fn(QuestionDomainObject $question) => $question->getBelongsTo() === QuestionBelongsTo::ORDER->name
        );

        $productQuestions = $questions->filter(
            fn(QuestionDomainObject $question) => $question->getBelongsTo() === QuestionBelongsTo::PRODUCT->name
        );

        $products = $this->productRepository
            ->loadRelation(ProductPriceDomainObject::class)
            ->findWhere(
                [ProductDomainObjectAbstract::EVENT_ID => $this->route->parameter('event_id')]
            );

        /** @var EventSettingDomainObject $eventSettings */
        $eventSettings = $this->eventSettingsRepository->findFirstWhere([
            'event_id' => $this->route->parameter('event_id'),
        ]);

        $addressRules = $eventSettings->getRequireBillingAddress() ? [
            'order.address' => 'array',
            'order.address.address_line_1' => 'required|string|max:255',
            'order.address.address_line_2' => 'nullable|string|max:255',
            'order.address.city' => 'required|string|max:85',
            'order.address.state_or_region' => 'nullable|string|max:85',
            'order.address.zip_or_postal_code' => 'nullable|string|max:85',
            'order.address.country' => 'required|string|max:2',
        ] : [];

        return [
            'order.first_name' => ['required', 'string', 'max:40'],
            'order.last_name' => ['required', 'string', 'max:40'],
            'order.questions' => new OrderQuestionRule($orderQuestions, $products),
            'order.email' => 'required|email',
            'order.email_confirmation' => 'required|email|same:order.email',
            'products' => new ProductQuestionRule(
                $productQuestions,
                $products,
                $eventSettings->getAttendeeDetailsCollectionMethod(),
            ),
            ...$addressRules
        ];
    }

    public function messages(): array
    {
        return [
            'order.first_name.max' => 'First name must be under 40 characters',
            'order.last_name.max' => 'Last name must be under 40 characters',
            'order.first_name.required' => __('First name is required'),
            'order.last_name.required' => __('Last name is required'),
            'order.email' => __('A valid email is required'),
            'order.email_confirmation.required' => __('Please confirm your email address'),
            'order.email_confirmation.same' => __('Email addresses do not match'),
            'order.address.address_line_1.required' => __('Address line 1 is required'),
            'order.address.city.required' => __('City is required'),
            'order.address.zip_or_postal_code.required' => __('Zip or postal code is required'),
            'order.address.country.required' => __('Country is required'),
        ];
    }
}
