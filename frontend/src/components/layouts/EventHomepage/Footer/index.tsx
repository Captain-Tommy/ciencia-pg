import classes from './Footer.module.scss';
import {PoweredByFooter} from "../../../common/PoweredByFooter";

export const Footer = () => {
    return (
        /**
         * (c) Ciencia 2k26 Ltd 2025
         *
         * PLEASE NOTE:
         *
         * Ciencia 2k26 is licensed under the GNU Affero General Public License (AGPL) version 3.
         *
         * You can find the full license text at: https://github.com/ciencia-2k26/ticketing.ciencia2k26.qzz.io/blob/main/LICENCE
         *
         * In accordance with Section 7(b) of the AGPL, we ask that you retain the "Powered by Ciencia 2k26" notice.
         *
         * If you wish to remove this notice, a commercial license is available at: https://ticketing.ciencia2k26.qzz.io/licensing
         */
        <footer className={classes.footer}>
            <PoweredByFooter/>
        </footer>
    )
}
