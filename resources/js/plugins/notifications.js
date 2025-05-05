import { router, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();
export const notifications = () => {
    router.on('finish', () => {
        const notification = usePage().props.notification;

        if (!notification.type || !notification.body) {
            return;
        }

        let options = {
            type: notification.type,
            position: 'bottom-right',
            //timeout: 5000,
            closeOnClick: true,
            // pauseOnFocusLoss: true,
            // pauseOnHover: true,
            // draggable: true,
            draggablePercent: 50,
            showCloseButtonOnHover: true,
            hideProgressBar: true,
            //closeButton: 'button',
            //icon: true,
            //rtl: false,
        };

        switch (notification.type) {
            case 'error':
            case 'info':
            case 'warning':
                options.timeout = 0;
                break;
            case 'success':
                options.timeout = 3000;
                break;
            default:
                options.timeout = 5000;
        }

        toast(notification.body, options);
    });
};
