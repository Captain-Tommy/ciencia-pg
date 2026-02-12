import {t} from '@lingui/macro';
import {IconCalendarPlus} from '@tabler/icons-react';
import {GenericErrorPage} from "../../../common/GenericErrorPage";
import {isCiencia} from "../../../../utilites/helpers.ts";

export const EventNotAvailable = () => {
    return (
        <GenericErrorPage
            title={t`Event Not Available`}
            description={t`The event you're looking for is not available at the moment. It may have been removed, expired, or the URL might be incorrect.`}
            pageTitle={t`Event Not Available`}
            metaDescription={t`The event you're looking for is not available at the moment. It may have been removed, expired, or the URL might be incorrect.`}
            buttonText={isCiencia() ? t`Create your own event` : undefined}
            buttonUrl={isCiencia() ? "https://app.ticketing.ciencia2k26.qzz.io/auth/register?utm_source=app.ticketing.ciencia2k26.qzz.io&utm_content=organizer-not-found/create-event" : undefined}
            buttonIcon={<IconCalendarPlus size={18}/>}
        />
    );
};

export default EventNotAvailable;
