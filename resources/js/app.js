import './bootstrap';

import Alpine from 'alpinejs';
import audioPlayer from './components/audioPlayer';
import exerciseAttempt from './components/exerciseAttempt';
import keyboardShortcuts from './components/keyboardShortcuts';
import notificationService from './notification-service';

window.Alpine = Alpine;

// Register Alpine.js components
Alpine.data('audioPlayer', audioPlayer);
Alpine.data('exerciseAttempt', exerciseAttempt);
Alpine.data('keyboardShortcuts', keyboardShortcuts);

Alpine.start();

// Initialize notification service if user is authenticated
document.addEventListener('DOMContentLoaded', () => {
    const notificationConfig = window.notificationConfig;
    if (notificationConfig && notificationConfig.userId) {
        notificationService.initialize(notificationConfig);
    }
});
