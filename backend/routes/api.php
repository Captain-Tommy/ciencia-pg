<?php

use Ciencia\Http\Actions\Accounts\CreateAccountAction;
use Ciencia\Http\Actions\Accounts\GetAccountAction;
use Ciencia\Http\Actions\Accounts\Stripe\CreateStripeConnectAccountAction;
use Ciencia\Http\Actions\Accounts\Stripe\GetStripeConnectAccountsAction;
use Ciencia\Http\Actions\Accounts\UpdateAccountAction;
use Ciencia\Http\Actions\Accounts\Vat\GetAccountVatSettingAction;
use Ciencia\Http\Actions\Accounts\Vat\UpsertAccountVatSettingAction;
use Ciencia\Http\Actions\Affiliates\CreateAffiliateAction;
use Ciencia\Http\Actions\Affiliates\DeleteAffiliateAction;
use Ciencia\Http\Actions\Affiliates\ExportAffiliatesAction;
use Ciencia\Http\Actions\Affiliates\GetAffiliateAction;
use Ciencia\Http\Actions\Affiliates\GetAffiliatesAction;
use Ciencia\Http\Actions\Affiliates\UpdateAffiliateAction;
use Ciencia\Http\Actions\Attendees\CheckInAttendeeAction;
use Ciencia\Http\Actions\Attendees\CreateAttendeeAction;
use Ciencia\Http\Actions\Attendees\EditAttendeeAction;
use Ciencia\Http\Actions\Attendees\ExportAttendeesAction;
use Ciencia\Http\Actions\Attendees\GetAttendeeAction;
use Ciencia\Http\Actions\Attendees\GetAttendeeActionPublic;
use Ciencia\Http\Actions\Attendees\GetAttendeesAction;
use Ciencia\Http\Actions\Attendees\PartialEditAttendeeAction;
use Ciencia\Http\Actions\Attendees\ResendAttendeeTicketAction;
use Ciencia\Http\Actions\Auth\AcceptInvitationAction;
use Ciencia\Http\Actions\Auth\ForgotPasswordAction;
use Ciencia\Http\Actions\Auth\GetUserInvitationAction;
use Ciencia\Http\Actions\Auth\LoginAction;
use Ciencia\Http\Actions\Auth\LogoutAction;
use Ciencia\Http\Actions\Auth\RefreshTokenAction;
use Ciencia\Http\Actions\Auth\ResetPasswordAction;
use Ciencia\Http\Actions\Auth\ValidateResetPasswordTokenAction;
use Ciencia\Http\Actions\CapacityAssignments\CreateCapacityAssignmentAction;
use Ciencia\Http\Actions\CapacityAssignments\DeleteCapacityAssignmentAction;
use Ciencia\Http\Actions\CapacityAssignments\GetCapacityAssignmentAction;
use Ciencia\Http\Actions\CapacityAssignments\GetCapacityAssignmentsAction;
use Ciencia\Http\Actions\CapacityAssignments\UpdateCapacityAssignmentAction;
use Ciencia\Http\Actions\CheckInLists\CreateCheckInListAction;
use Ciencia\Http\Actions\CheckInLists\DeleteCheckInListAction;
use Ciencia\Http\Actions\CheckInLists\GetCheckInListAction;
use Ciencia\Http\Actions\CheckInLists\GetCheckInListsAction;
use Ciencia\Http\Actions\CheckInLists\Public\CreateAttendeeCheckInPublicAction;
use Ciencia\Http\Actions\CheckInLists\Public\DeleteAttendeeCheckInPublicAction;
use Ciencia\Http\Actions\CheckInLists\Public\GetCheckInListAttendeePublicAction;
use Ciencia\Http\Actions\CheckInLists\Public\GetCheckInListAttendeesPublicAction;
use Ciencia\Http\Actions\CheckInLists\Public\GetCheckInListPublicAction;
use Ciencia\Http\Actions\CheckInLists\UpdateCheckInListAction;
use Ciencia\Http\Actions\Common\GetColorThemesAction;
use Ciencia\Http\Actions\Common\Webhooks\StripeIncomingWebhookAction;
use Ciencia\Http\Actions\Events\CreateEventAction;
use Ciencia\Http\Actions\Events\DuplicateEventAction;
use Ciencia\Http\Actions\Events\GetEventAction;
use Ciencia\Http\Actions\Events\GetEventPublicAction;
use Ciencia\Http\Actions\Events\GetEventsAction;
use Ciencia\Http\Actions\Events\GetOrganizerEventsPublicAction;
use Ciencia\Http\Actions\Events\Images\CreateEventImageAction;
use Ciencia\Http\Actions\Events\Images\DeleteEventImageAction;
use Ciencia\Http\Actions\Events\Images\GetEventImagesAction;
use Ciencia\Http\Actions\Events\Stats\GetEventStatsAction;
use Ciencia\Http\Actions\Events\UpdateEventAction;
use Ciencia\Http\Actions\Events\UpdateEventStatusAction;
use Ciencia\Http\Actions\EventSettings\EditEventSettingsAction;
use Ciencia\Http\Actions\EventSettings\GetEventSettingsAction;
use Ciencia\Http\Actions\EmailTemplates\CreateOrganizerEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\CreateEventEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\UpdateOrganizerEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\UpdateEventEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\GetOrganizerEmailTemplatesAction;
use Ciencia\Http\Actions\EmailTemplates\GetEventEmailTemplatesAction;
use Ciencia\Http\Actions\EmailTemplates\DeleteOrganizerEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\DeleteEventEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\PreviewOrganizerEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\PreviewEventEmailTemplateAction;
use Ciencia\Http\Actions\EmailTemplates\GetAvailableTokensAction;
use Ciencia\Http\Actions\EmailTemplates\GetDefaultEmailTemplateAction;
use Ciencia\Http\Actions\EventSettings\PartialEditEventSettingsAction;
use Ciencia\Http\Actions\Images\CreateImageAction;
use Ciencia\Http\Actions\Images\DeleteImageAction;
use Ciencia\Http\Actions\Messages\CancelMessageAction;
use Ciencia\Http\Actions\Messages\GetMessageRecipientsAction;
use Ciencia\Http\Actions\Messages\GetMessagesAction;
use Ciencia\Http\Actions\Messages\SendMessageAction;
use Ciencia\Http\Actions\Orders\CancelOrderAction;
use Ciencia\Http\Actions\Orders\DownloadOrderInvoiceAction;
use Ciencia\Http\Actions\Orders\EditOrderAction;
use Ciencia\Http\Actions\Orders\ExportOrdersAction;
use Ciencia\Http\Actions\Orders\GetOrderAction;
use Ciencia\Http\Actions\Orders\GetOrdersAction;
use Ciencia\Http\Actions\Orders\MarkOrderAsPaidAction;
use Ciencia\Http\Actions\Orders\MessageOrderAction;
use Ciencia\Http\Actions\Orders\Payment\RefundOrderAction;
use Ciencia\Http\Actions\Orders\Payment\Stripe\CreatePaymentIntentActionPublic;
use Ciencia\Http\Actions\Orders\Payment\Stripe\GetPaymentIntentActionPublic;
use Ciencia\Http\Actions\Orders\Public\AbandonOrderActionPublic;
use Ciencia\Http\Actions\Orders\Public\CompleteOrderActionPublic;
use Ciencia\Http\Actions\Orders\Public\CreateOrderActionPublic;
use Ciencia\Http\Actions\Orders\Public\DownloadOrderInvoicePublicAction;
use Ciencia\Http\Actions\Orders\Public\GetOrderActionPublic;
use Ciencia\Http\Actions\Orders\Public\TransitionOrderToOfflinePaymentPublicAction;
use Ciencia\Http\Actions\Orders\ResendOrderConfirmationAction;
use Ciencia\Http\Actions\Organizers\CreateOrganizerAction;
use Ciencia\Http\Actions\SelfService\EditAttendeePublicAction;
use Ciencia\Http\Actions\SelfService\EditOrderPublicAction;
use Ciencia\Http\Actions\SelfService\ResendAttendeeTicketPublicAction;
use Ciencia\Http\Actions\SelfService\ResendOrderConfirmationPublicAction;
use Ciencia\Http\Actions\Organizers\EditOrganizerAction;
use Ciencia\Http\Actions\Organizers\GetOrganizerAction;
use Ciencia\Http\Actions\Organizers\GetOrganizerEventsAction;
use Ciencia\Http\Actions\Organizers\GetOrganizersAction;
use Ciencia\Http\Actions\Organizers\GetPublicOrganizerAction;
use Ciencia\Http\Actions\Organizers\Orders\GetOrganizerOrdersAction;
use Ciencia\Http\Actions\Organizers\Public\SendOrganizerContactMessagePublicAction;
use Ciencia\Http\Actions\Organizers\Settings\GetOrganizerSettingsAction;
use Ciencia\Http\Actions\Organizers\Settings\PartialUpdateOrganizerSettingsAction;
use Ciencia\Http\Actions\Organizers\Stats\GetOrganizerStatsAction;
use Ciencia\Http\Actions\Organizers\UpdateOrganizerStatusAction;
use Ciencia\Http\Actions\ProductCategories\CreateProductCategoryAction;
use Ciencia\Http\Actions\ProductCategories\DeleteProductCategoryAction;
use Ciencia\Http\Actions\ProductCategories\EditProductCategoryAction;
use Ciencia\Http\Actions\ProductCategories\GetProductCategoriesAction;
use Ciencia\Http\Actions\ProductCategories\GetProductCategoryAction;
use Ciencia\Http\Actions\Products\CreateProductAction;
use Ciencia\Http\Actions\Products\DeleteProductAction;
use Ciencia\Http\Actions\Products\EditProductAction;
use Ciencia\Http\Actions\Products\GetProductAction;
use Ciencia\Http\Actions\Products\GetProductsAction;
use Ciencia\Http\Actions\Products\SortProductsAction;
use Ciencia\Http\Actions\PromoCodes\CreatePromoCodeAction;
use Ciencia\Http\Actions\PromoCodes\DeletePromoCodeAction;
use Ciencia\Http\Actions\PromoCodes\GetPromoCodeAction;
use Ciencia\Http\Actions\PromoCodes\GetPromoCodePublic;
use Ciencia\Http\Actions\PromoCodes\GetPromoCodesAction;
use Ciencia\Http\Actions\PromoCodes\UpdatePromoCodeAction;
use Ciencia\Http\Actions\Questions\CreateQuestionAction;
use Ciencia\Http\Actions\Questions\DeleteQuestionAction;
use Ciencia\Http\Actions\Questions\EditQuestionAction;
use Ciencia\Http\Actions\Questions\EditQuestionAnswerAction;
use Ciencia\Http\Actions\Questions\ExportQuestionAnswersAction;
use Ciencia\Http\Actions\Questions\GetQuestionAction;
use Ciencia\Http\Actions\Questions\GetQuestionsAction;
use Ciencia\Http\Actions\Questions\GetQuestionsPublicAction;
use Ciencia\Http\Actions\Questions\SortQuestionsAction;
use Ciencia\Http\Actions\Reports\ExportOrganizerReportAction;
use Ciencia\Http\Actions\Reports\GetOrganizerReportAction;
use Ciencia\Http\Actions\Reports\GetReportAction;
use Ciencia\Http\Actions\Sitemap\GetSitemapEventsAction;
use Ciencia\Http\Actions\Sitemap\GetSitemapIndexAction;
use Ciencia\Http\Actions\Sitemap\GetSitemapOrganizersAction;
use Ciencia\Http\Actions\TaxesAndFees\CreateTaxOrFeeAction;
use Ciencia\Http\Actions\TaxesAndFees\DeleteTaxOrFeeAction;
use Ciencia\Http\Actions\TaxesAndFees\EditTaxOrFeeAction;
use Ciencia\Http\Actions\TaxesAndFees\GetTaxOrFeeAction;
use Ciencia\Http\Actions\Users\CancelEmailChangeAction;
use Ciencia\Http\Actions\Users\ConfirmEmailAddressAction;
use Ciencia\Http\Actions\Users\ConfirmEmailChangeAction;
use Ciencia\Http\Actions\Users\ConfirmEmailWithCodeAction;
use Ciencia\Http\Actions\Users\CreateUserAction;
use Ciencia\Http\Actions\Users\DeleteInvitationAction;
use Ciencia\Http\Actions\Users\GetMeAction;
use Ciencia\Http\Actions\Users\GetUserAction;
use Ciencia\Http\Actions\Users\GetUsersAction;
use Ciencia\Http\Actions\Users\ResendEmailConfirmationAction;
use Ciencia\Http\Actions\Users\ResendInvitationAction;
use Ciencia\Http\Actions\Users\UpdateMeAction;
use Ciencia\Http\Actions\Users\UpdateUserAction;
use Ciencia\Http\Actions\Admin\Accounts\AssignConfigurationAction;
use Ciencia\Http\Actions\Admin\Accounts\GetAccountAction as GetAdminAccountAction;
use Ciencia\Http\Actions\Admin\Accounts\GetAllAccountsAction as GetAllAdminAccountsAction;
use Ciencia\Http\Actions\Admin\Accounts\UpdateAccountVatSettingAction as UpdateAdminAccountVatSettingAction;
use Ciencia\Http\Actions\Admin\Configurations\CreateConfigurationAction;
use Ciencia\Http\Actions\Admin\Configurations\DeleteConfigurationAction;
use Ciencia\Http\Actions\Admin\Configurations\GetAllConfigurationsAction;
use Ciencia\Http\Actions\Admin\Configurations\UpdateConfigurationAction;
use Ciencia\Http\Actions\Admin\Events\GetAllEventsAction as GetAllAdminEventsAction;
use Ciencia\Http\Actions\Admin\Events\GetUpcomingEventsAction;
use Ciencia\Http\Actions\Admin\FailedJobs\DeleteAllFailedJobsAction;
use Ciencia\Http\Actions\Admin\FailedJobs\DeleteFailedJobAction;
use Ciencia\Http\Actions\Admin\FailedJobs\GetAllFailedJobsAction;
use Ciencia\Http\Actions\Admin\FailedJobs\RetryAllFailedJobsAction;
use Ciencia\Http\Actions\Admin\FailedJobs\RetryFailedJobAction;
use Ciencia\Http\Actions\Admin\Messages\ApproveMessageAction;
use Ciencia\Http\Actions\Admin\Messages\GetAllMessagesAction as GetAllAdminMessagesAction;
use Ciencia\Http\Actions\Admin\GetMessagingTiersAction;
use Ciencia\Http\Actions\Admin\Accounts\UpdateAccountMessagingTierAction;
use Ciencia\Http\Actions\Admin\Orders\GetAllOrdersAction;
use Ciencia\Http\Actions\Admin\Attribution\GetUtmAttributionStatsAction;
use Ciencia\Http\Actions\Admin\Stats\GetAdminDashboardDataAction;
use Ciencia\Http\Actions\Admin\Stats\GetAdminStatsAction;
use Ciencia\Http\Actions\Admin\Users\GetAllUsersAction;
use Ciencia\Http\Actions\Admin\Users\StartImpersonationAction;
use Ciencia\Http\Actions\Admin\Users\StopImpersonationAction;
use Ciencia\Http\Actions\TicketLookup\GetOrdersByLookupTokenAction;
use Ciencia\Http\Actions\TicketLookup\SendTicketLookupEmailAction;
use Ciencia\Http\Actions\Webhooks\CreateWebhookAction;
use Ciencia\Http\Actions\Webhooks\DeleteWebhookAction;
use Ciencia\Http\Actions\Webhooks\EditWebhookAction;
use Ciencia\Http\Actions\Webhooks\GetWebhookAction;
use Ciencia\Http\Actions\Webhooks\GetWebhookLogsAction;
use Ciencia\Http\Actions\Webhooks\GetWebhooksAction;
use Illuminate\Routing\Router;

/** @var Router|Router $router */
$router = app()->get('router');

$router->prefix('/auth')->group(
    function (Router $router): void {
        // Auth
        $router->post('/login', LoginAction::class)->name('auth.login');
        $router->post('/logout', LogoutAction::class)->name('auth.logout');
        $router->post('/register', CreateAccountAction::class)->name('auth.register');
        $router->post('/forgot-password', ForgotPasswordAction::class)->name('auth.forgot-password');

        // Invitations
        $router->get('/invitation/{invite_token}', GetUserInvitationAction::class)->name('auth.invitation');
        $router->post('/invitation/{invite_token}', AcceptInvitationAction::class)->name('auth.accept-invitation');

        // Reset Passwords
        $router->get('/reset-password/{reset_token}', ValidateResetPasswordTokenAction::class)->name('auth.validate-reset-password-token');
        $router->post('/reset-password/{reset_token}', ResetPasswordAction::class)->name('auth.reset-password');
    }
);

/**
 * Logged In Routes
 */
$router->middleware(['auth:api'])->group(
    function (Router $router): void {
        // Auth
        $router->get('/auth/logout', LogoutAction::class);
        $router->post('/auth/refresh', RefreshTokenAction::class);

        // Users
        $router->get('/users/me', GetMeAction::class);
        $router->put('/users/me', UpdateMeAction::class);
        $router->post('/users', CreateUserAction::class);
        $router->get('/users', GetUsersAction::class);
        $router->get('/users/{user_id}', GetUserAction::class);
        $router->put('/users/{user_id}', UpdateUserAction::class);
        $router->post('/users/{user_id}/email-change/{changeToken}', ConfirmEmailChangeAction::class);
        $router->post('/users/{user_id}/invitation', ResendInvitationAction::class);
        $router->delete('/users/{user_id}/invitation', DeleteInvitationAction::class);
        $router->delete('/users/{user_id}/email-change', CancelEmailChangeAction::class);
        $router->post('/users/{user_id}/confirm-email/{resetToken}', ConfirmEmailAddressAction::class);
        $router->post('/users/{user_id}/resend-email-confirmation', ResendEmailConfirmationAction::class);
        $router->post('/users/{user_id}/confirm-email-with-code', ConfirmEmailWithCodeAction::class);

        // Accounts
        $router->get('/accounts/{account_id?}', GetAccountAction::class);
        $router->put('/accounts/{account_id?}', UpdateAccountAction::class);
        $router->get('/accounts/{account_id}/stripe/connect_accounts', GetStripeConnectAccountsAction::class);
        $router->post('/accounts/{account_id}/stripe/connect', CreateStripeConnectAccountAction::class);

        // VAT Settings
        $router->get('/accounts/{account_id}/vat-settings', GetAccountVatSettingAction::class);
        $router->post('/accounts/{account_id}/vat-settings', UpsertAccountVatSettingAction::class);

        // Organizers
        $router->post('/organizers', CreateOrganizerAction::class);
        // This is POST instead of PUT because you can't upload files via PUT in PHP (at least not easily)
        $router->post('/organizers/{organizer_id}', EditOrganizerAction::class);
        $router->put('/organizers/{organizer_id}/status', UpdateOrganizerStatusAction::class);
        $router->get('/organizers', GetOrganizersAction::class);
        $router->get('/organizers/{organizer_id}', GetOrganizerAction::class);
        $router->get('/organizers/{organizer_id}/events', GetOrganizerEventsAction::class);
        $router->get('/organizers/{organizer_id}/stats', GetOrganizerStatsAction::class);
        $router->get('/organizers/{organizer_id}/orders', GetOrganizerOrdersAction::class);
        $router->get('/organizers/{organizer_id}/settings', GetOrganizerSettingsAction::class);
        $router->patch('/organizers/{organizer_id}/settings', PartialUpdateOrganizerSettingsAction::class);
        $router->get('/organizers/{organizer_id}/reports/{report_type}', GetOrganizerReportAction::class);
        $router->get('/organizers/{organizer_id}/reports/{report_type}/export', ExportOrganizerReportAction::class);

        // Email Templates - Organizer level
        $router->get('/organizers/{organizerId}/email-templates', GetOrganizerEmailTemplatesAction::class);
        $router->get('/email-templates/defaults', GetDefaultEmailTemplateAction::class);
        $router->post('/organizers/{organizerId}/email-templates', CreateOrganizerEmailTemplateAction::class);
        $router->put('/organizers/{organizerId}/email-templates/{templateId}', UpdateOrganizerEmailTemplateAction::class);
        $router->delete('/organizers/{organizerId}/email-templates/{templateId}', DeleteOrganizerEmailTemplateAction::class);
        $router->post('/organizers/{organizerId}/email-templates/preview', PreviewOrganizerEmailTemplateAction::class);
        $router->get('/email-templates/tokens/{templateType}', GetAvailableTokensAction::class);

        // Taxes and Fees
        $router->post('/accounts/{account_id}/taxes-and-fees', CreateTaxOrFeeAction::class);
        $router->get('/accounts/{account_id}/taxes-and-fees', GetTaxOrFeeAction::class);
        $router->put('/accounts/{account_id}/taxes-and-fees/{tax_or_fee_id}', EditTaxOrFeeAction::class);
        $router->delete('/accounts/{account_id}/taxes-and-fees/{tax_or_fee_id}', DeleteTaxOrFeeAction::class);

        // Events
        $router->post('/events', CreateEventAction::class);
        $router->get('/events', GetEventsAction::class);
        $router->get('/events/{event_id}', GetEventAction::class);
        $router->put('/events/{event_id}', UpdateEventAction::class);
        $router->put('/events/{event_id}/status', UpdateEventStatusAction::class);
        $router->post('/events/{event_id}/duplicate', DuplicateEventAction::class);

        // Product Categories
        $router->post('/events/{event_id}/product-categories', CreateProductCategoryAction::class);
        $router->get('/events/{event_id}/product-categories', GetProductCategoriesAction::class);
        $router->get('/events/{event_id}/product-categories/{category_id}', GetProductCategoryAction::class);
        $router->put('/events/{event_id}/product-categories/{category_id}', EditProductCategoryAction::class);
        $router->delete('/events/{event_id}/product-categories/{category_id}', DeleteProductCategoryAction::class);

        // Products
        $router->post('/events/{event_id}/products', CreateProductAction::class);
        $router->post('/events/{event_id}/products/sort', SortProductsAction::class);
        $router->put('/events/{event_id}/products/{ticket_id}', EditProductAction::class);
        $router->get('/events/{event_id}/products/{ticket_id}', GetProductAction::class);
        $router->delete('/events/{event_id}/products/{ticket_id}', DeleteProductAction::class);
        $router->get('/events/{event_id}/products', GetProductsAction::class);

        // Stats
        $router->get('/events/{event_id}/stats', GetEventStatsAction::class);

        // Email Templates - Event level
        $router->get('/events/{eventId}/email-templates', GetEventEmailTemplatesAction::class);
        $router->post('/events/{eventId}/email-templates', CreateEventEmailTemplateAction::class);
        $router->put('/events/{eventId}/email-templates/{templateId}', UpdateEventEmailTemplateAction::class);
        $router->delete('/events/{eventId}/email-templates/{templateId}', DeleteEventEmailTemplateAction::class);
        $router->post('/events/{eventId}/email-templates/preview', PreviewEventEmailTemplateAction::class);

        // Attendees
        $router->post('/events/{event_id}/attendees', CreateAttendeeAction::class);
        $router->get('/events/{event_id}/attendees', GetAttendeesAction::class);
        $router->get('/events/{event_id}/attendees/{attendee_id}', GetAttendeeAction::class);
        $router->put('/events/{event_id}/attendees/{attendee_id}', EditAttendeeAction::class);
        $router->patch('/events/{event_id}/attendees/{attendee_id}', PartialEditAttendeeAction::class);
        $router->post('/events/{event_id}/attendees/export', ExportAttendeesAction::class);
        $router->post('/events/{event_id}/attendees/{attendee_public_id}/resend-ticket', ResendAttendeeTicketAction::class);
        $router->post('/events/{event_id}/attendees/{attendee_public_id}/check_in', CheckInAttendeeAction::class);

        // Orders
        $router->get('/events/{event_id}/orders', GetOrdersAction::class);
        $router->get('/events/{event_id}/orders/{order_id}', GetOrderAction::class);
        $router->put('/events/{event_id}/orders/{order_id}', EditOrderAction::class);
        $router->post('/events/{event_id}/orders/{order_id}/message', MessageOrderAction::class);
        $router->post('/events/{event_id}/orders/{order_id}/refund', RefundOrderAction::class);
        $router->post('/events/{event_id}/orders/{order_id}/resend_confirmation', ResendOrderConfirmationAction::class);
        $router->post('/events/{event_id}/orders/{order_id}/cancel', CancelOrderAction::class);
        $router->post('/events/{event_id}/orders/{order_id}/mark-as-paid', MarkOrderAsPaidAction::class);
        $router->post('/events/{event_id}/orders/export', ExportOrdersAction::class);
        $router->get('/events/{event_id}/orders/{order_id}/invoice', DownloadOrderInvoiceAction::class);

        // Questions
        $router->post('/events/{event_id}/questions', CreateQuestionAction::class);
        $router->put('/events/{event_id}/questions/{question_id}', EditQuestionAction::class);
        $router->get('/events/{event_id}/questions/{question_id}', GetQuestionAction::class);
        $router->delete('/events/{event_id}/questions/{question_id}', DeleteQuestionAction::class);
        $router->get('/events/{event_id}/questions', GetQuestionsAction::class);
        $router->post('/events/{event_id}/questions/export', ExportOrdersAction::class);
        $router->post('/events/{event_id}/questions/sort', SortQuestionsAction::class);
        $router->put('/events/{event_id}/questions/{question_id}/answers/{answer_id}', EditQuestionAnswerAction::class);
        $router->match(['get', 'post'], '/events/{event_id}/questions/answers/export', ExportQuestionAnswersAction::class);

        // Images
        $router->post('/events/{event_id}/images', CreateEventImageAction::class);
        $router->get('/events/{event_id}/images', GetEventImagesAction::class);
        $router->delete('/events/{event_id}/images/{image_id}', DeleteEventImageAction::class);

        // Promo Codes
        $router->post('/events/{event_id}/promo-codes', CreatePromoCodeAction::class);
        $router->put('/events/{event_id}/promo-codes/{promo_code_id}', UpdatePromoCodeAction::class);
        $router->get('/events/{event_id}/promo-codes', GetPromoCodesAction::class);
        $router->get('/events/{event_id}/promo-codes/{promo_code_id}', GetPromoCodeAction::class);
        $router->delete('/events/{event_id}/promo-codes/{promo_code_id}', DeletePromoCodeAction::class);

        // Affiliates
        $router->post('/events/{event_id}/affiliates', CreateAffiliateAction::class);
        $router->put('/events/{event_id}/affiliates/{affiliate_id}', UpdateAffiliateAction::class);
        $router->get('/events/{event_id}/affiliates', GetAffiliatesAction::class);
        $router->get('/events/{event_id}/affiliates/{affiliate_id}', GetAffiliateAction::class);
        $router->delete('/events/{event_id}/affiliates/{affiliate_id}', DeleteAffiliateAction::class);
        $router->post('/events/{event_id}/affiliates/export', ExportAffiliatesAction::class);

        // Messages
        $router->post('/events/{event_id}/messages', SendMessageAction::class);
        $router->get('/events/{event_id}/messages', GetMessagesAction::class);
        $router->post('/events/{event_id}/messages/{message_id}/cancel', CancelMessageAction::class);
        $router->get('/events/{event_id}/messages/{message_id}/recipients', GetMessageRecipientsAction::class);

        // Event Settings
        $router->get('/events/{event_id}/settings', GetEventSettingsAction::class);
        $router->put('/events/{event_id}/settings', EditEventSettingsAction::class);
        $router->patch('/events/{event_id}/settings', PartialEditEventSettingsAction::class);

        // Capacity Assignments
        $router->post('/events/{event_id}/capacity-assignments', CreateCapacityAssignmentAction::class);
        $router->get('/events/{event_id}/capacity-assignments', GetCapacityAssignmentsAction::class);
        $router->get('/events/{event_id}/capacity-assignments/{capacity_assignment_id}', GetCapacityAssignmentAction::class);
        $router->put('/events/{event_id}/capacity-assignments/{capacity_assignment_id}', UpdateCapacityAssignmentAction::class);
        $router->delete('/events/{event_id}/capacity-assignments/{capacity_assignment_id}', DeleteCapacityAssignmentAction::class);

        // Check-In Lists
        $router->post('/events/{event_id}/check-in-lists', CreateCheckInListAction::class);
        $router->get('/events/{event_id}/check-in-lists', GetCheckInListsAction::class);
        $router->get('/events/{event_id}/check-in-lists/{check_in_list_id}', GetCheckInListAction::class);
        $router->put('/events/{event_id}/check-in-lists/{check_in_list_id}', UpdateCheckInListAction::class);
        $router->delete('/events/{event_id}/check-in-lists/{check_in_list_id}', DeleteCheckInListAction::class);

        // Webhooks
        $router->post('/events/{event_id}/webhooks', CreateWebhookAction::class);
        $router->get('/events/{event_id}/webhooks', GetWebhooksAction::class);
        $router->put('/events/{event_id}/webhooks/{webhook_id}', EditWebhookAction::class);
        $router->get('/events/{event_id}/webhooks/{webhook_id}', GetWebhookAction::class);
        $router->delete('/events/{event_id}/webhooks/{webhook_id}', DeleteWebhookAction::class);
        $router->get('/events/{event_id}/webhooks/{webhook_id}/logs', GetWebhookLogsAction::class);

        // Reports
        $router->get('/events/{event_id}/reports/{report_type}', GetReportAction::class);

        // Images
        $router->post('/images', CreateImageAction::class);
        $router->delete('/images/{image_id}', DeleteImageAction::class);
    }
);

$router->prefix('/admin')->middleware(['auth:api'])->group(
    function (Router $router): void {
        $router->get('/stats', GetAdminStatsAction::class);
        $router->get('/dashboard', GetAdminDashboardDataAction::class);
        $router->get('/attribution/stats', GetUtmAttributionStatsAction::class);
        $router->get('/accounts', GetAllAdminAccountsAction::class);
        $router->get('/accounts/{account_id}', GetAdminAccountAction::class);
        $router->put('/accounts/{account_id}/vat-settings', UpdateAdminAccountVatSettingAction::class);
        $router->put('/accounts/{account_id}/configuration', AssignConfigurationAction::class);
        $router->get('/configurations', GetAllConfigurationsAction::class);
        $router->post('/configurations', CreateConfigurationAction::class);
        $router->put('/configurations/{configuration_id}', UpdateConfigurationAction::class);
        $router->delete('/configurations/{configuration_id}', DeleteConfigurationAction::class);
        $router->get('/users', GetAllUsersAction::class);
        $router->get('/events', GetAllAdminEventsAction::class);
        $router->get('/events/upcoming', GetUpcomingEventsAction::class);
        $router->get('/orders', GetAllOrdersAction::class);
        $router->post('/impersonate/{user_id}', StartImpersonationAction::class);
        $router->post('/stop-impersonation', StopImpersonationAction::class);

        // Failed Jobs
        $router->get('/failed-jobs', GetAllFailedJobsAction::class);
        $router->delete('/failed-jobs/{jobId}', DeleteFailedJobAction::class);
        $router->delete('/failed-jobs', DeleteAllFailedJobsAction::class);
        $router->post('/failed-jobs/{jobId}/retry', RetryFailedJobAction::class);
        $router->post('/failed-jobs/retry-all', RetryAllFailedJobsAction::class);

        // Messages
        $router->get('/messages', GetAllAdminMessagesAction::class);
        $router->post('/messages/{message_id}/approve', ApproveMessageAction::class);

        // Messaging Tiers
        $router->get('/messaging-tiers', GetMessagingTiersAction::class);
        $router->put('/accounts/{account_id}/messaging-tier', UpdateAccountMessagingTierAction::class);
    }
);

/**
 * Public routes
 */
$router->prefix('/public')->group(
    function (Router $router): void {
        // Events
        $router->get('/events/{event_id}', GetEventPublicAction::class);

        // Organizers
        $router->get('/organizers/{organizer_id}', GetPublicOrganizerAction::class);
        $router->get('/organizers/{organizer_id}/events', GetOrganizerEventsPublicAction::class);
        $router->post('/organizers/{organizer_id}/contact', SendOrganizerContactMessagePublicAction::class);

        // Products
        $router->get('/events/{event_id}/products', GetEventPublicAction::class);

        // Orders
        $router->post('/events/{event_id}/order', CreateOrderActionPublic::class);
        $router->put('/events/{event_id}/order/{order_short_id}', CompleteOrderActionPublic::class);
        $router->get('/events/{event_id}/order/{order_short_id}', GetOrderActionPublic::class);
        $router->post('/events/{event_id}/order/{order_short_id}/abandon', AbandonOrderActionPublic::class);
        $router->post('/events/{event_id}/order/{order_short_id}/await-offline-payment', TransitionOrderToOfflinePaymentPublicAction::class);
        $router->get('/events/{event_id}/order/{order_short_id}/invoice', DownloadOrderInvoicePublicAction::class);

        // Attendees
        $router->get('/events/{event_id}/attendees/{attendee_short_id}', GetAttendeeActionPublic::class);

        // Promo codes
        $router->get('/events/{event_id}/promo-codes/{promo_code}', GetPromoCodePublic::class);

        // Stripe payment gateway
        $router->post('/events/{event_id}/order/{order_short_id}/stripe/payment_intent', CreatePaymentIntentActionPublic::class);
        $router->get('/events/{event_id}/order/{order_short_id}/stripe/payment_intent', GetPaymentIntentActionPublic::class);

        // Questions
        $router->get('/events/{event_id}/questions', GetQuestionsPublicAction::class);

        // Webhooks
        $router->post('/webhooks/stripe', StripeIncomingWebhookAction::class);

        // Check-In
        $router->get('/check-in-lists/{check_in_list_short_id}', GetCheckInListPublicAction::class);
        $router->get('/check-in-lists/{check_in_list_short_id}/attendees', GetCheckInListAttendeesPublicAction::class);
        $router->get('/check-in-lists/{check_in_list_short_id}/attendees/{attendee_public_id}', GetCheckInListAttendeePublicAction::class);
        $router->post('/check-in-lists/{check_in_list_short_id}/check-ins', CreateAttendeeCheckInPublicAction::class);
        $router->delete('/check-in-lists/{check_in_list_short_id}/check-ins/{check_in_short_id}', DeleteAttendeeCheckInPublicAction::class);

        // Color themes
        $router->get('/color-themes', GetColorThemesAction::class);

        // Ticket Lookup
        $router->post('/ticket-lookup', SendTicketLookupEmailAction::class);
        $router->get('/ticket-lookup/{token}', GetOrdersByLookupTokenAction::class);

        // Self-service order and attendee edits
        $router->prefix('/events/{event_id}/order/{order_short_id}')->group(function (Router $router): void {
            $router->patch('/', EditOrderPublicAction::class)->middleware('throttle:self-service-edit');
            $router->post('/resend-confirmation', ResendOrderConfirmationPublicAction::class)->middleware('throttle:self-service-email');

            $router->patch('/attendees/{attendee_short_id}', EditAttendeePublicAction::class)->middleware('throttle:self-service-edit');
            $router->post('/attendees/{attendee_short_id}/resend-ticket', ResendAttendeeTicketPublicAction::class)->middleware('throttle:self-service-email');
        });

        // Sitemap
        $router->get('/sitemap.xml', GetSitemapIndexAction::class);
        $router->get('/sitemap-events-{page}.xml', GetSitemapEventsAction::class)->where('page', '[0-9]+');
        $router->get('/sitemap-organizers-{page}.xml', GetSitemapOrganizersAction::class)->where('page', '[0-9]+');
    }
);

include_once __DIR__ . '/mail.php';
