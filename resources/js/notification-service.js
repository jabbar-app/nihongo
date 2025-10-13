/**
 * Study Reminder Notification Service
 * Handles browser notifications for daily study reminders
 */

class NotificationService {
    constructor() {
        this.permissionGranted = false;
        this.scheduledNotifications = new Map();
        this.checkInterval = null;
    }

    /**
     * Check if browser supports notifications
     */
    isSupported() {
        return 'Notification' in window;
    }

    /**
     * Get current notification permission status
     */
    getPermissionStatus() {
        if (!this.isSupported()) {
            return 'unsupported';
        }
        return Notification.permission;
    }

    /**
     * Request notification permission from user
     */
    async requestPermission() {
        if (!this.isSupported()) {
            console.warn('Browser does not support notifications');
            return false;
        }

        if (Notification.permission === 'granted') {
            this.permissionGranted = true;
            return true;
        }

        if (Notification.permission === 'denied') {
            console.warn('Notification permission denied');
            return false;
        }

        try {
            const permission = await Notification.requestPermission();
            this.permissionGranted = permission === 'granted';
            
            // Update server about permission status
            if (this.permissionGranted) {
                await this.updatePermissionStatus(true);
            }
            
            return this.permissionGranted;
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            return false;
        }
    }

    /**
     * Update server about notification permission status
     */
    async updatePermissionStatus(granted) {
        try {
            await fetch('/api/notification-permission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ granted }),
            });
        } catch (error) {
            console.error('Error updating permission status:', error);
        }
    }

    /**
     * Show a notification
     */
    showNotification(title, options = {}) {
        if (!this.isSupported() || Notification.permission !== 'granted') {
            console.warn('Cannot show notification: permission not granted');
            return null;
        }

        const defaultOptions = {
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            requireInteraction: false,
            ...options,
        };

        try {
            const notification = new Notification(title, defaultOptions);
            
            // Auto-close after 10 seconds if not requiring interaction
            if (!defaultOptions.requireInteraction) {
                setTimeout(() => notification.close(), 10000);
            }

            return notification;
        } catch (error) {
            console.error('Error showing notification:', error);
            return null;
        }
    }

    /**
     * Schedule a daily reminder
     */
    scheduleReminder(reminderTime, userId) {
        if (!reminderTime) {
            console.warn('No reminder time provided');
            return;
        }

        // Clear existing check interval
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }

        // Check every minute if it's time to show the reminder
        this.checkInterval = setInterval(() => {
            this.checkAndShowReminder(reminderTime, userId);
        }, 60000); // Check every minute

        // Also check immediately
        this.checkAndShowReminder(reminderTime, userId);
    }

    /**
     * Check if it's time to show reminder and show it
     */
    async checkAndShowReminder(reminderTime, userId) {
        const now = new Date();
        const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        
        // Check if current time matches reminder time
        if (currentTime === reminderTime) {
            // Check if we already showed notification today
            const lastShown = localStorage.getItem(`last_reminder_${userId}`);
            const today = now.toDateString();
            
            if (lastShown !== today) {
                await this.showStudyReminder(userId);
                localStorage.setItem(`last_reminder_${userId}`, today);
            }
        }
    }

    /**
     * Show study reminder with daily plan summary
     */
    async showStudyReminder(userId) {
        try {
            // Fetch daily plan summary
            const response = await fetch('/api/daily-plan-summary', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });
            
            const data = await response.json();
            
            const notification = this.showNotification('Time to Study Japanese! 📚', {
                body: data.summary || 'Your daily study plan is ready. Click to start learning!',
                icon: '/favicon.ico',
                tag: 'study-reminder',
                requireInteraction: false,
                actions: [
                    { action: 'start', title: 'Start Studying' },
                    { action: 'snooze', title: 'Snooze 1 hour' },
                ],
            });

            if (notification) {
                notification.onclick = () => {
                    window.focus();
                    window.location.href = '/study-plan';
                    notification.close();
                };
            }
        } catch (error) {
            console.error('Error showing study reminder:', error);
            // Fallback to simple notification
            const notification = this.showNotification('Time to Study Japanese! 📚', {
                body: 'Your daily study plan is ready. Click to start learning!',
                tag: 'study-reminder',
            });

            if (notification) {
                notification.onclick = () => {
                    window.focus();
                    window.location.href = '/study-plan';
                    notification.close();
                };
            }
        }
    }

    /**
     * Snooze reminder for specified minutes
     */
    snoozeReminder(minutes = 60) {
        const snoozeUntil = new Date(Date.now() + minutes * 60000);
        localStorage.setItem('reminder_snoozed_until', snoozeUntil.toISOString());
    }

    /**
     * Check if reminder is currently snoozed
     */
    isReminderSnoozed() {
        const snoozeUntil = localStorage.getItem('reminder_snoozed_until');
        if (!snoozeUntil) return false;
        
        return new Date(snoozeUntil) > new Date();
    }

    /**
     * Clear scheduled reminders
     */
    clearReminders() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
            this.checkInterval = null;
        }
        this.scheduledNotifications.clear();
    }

    /**
     * Initialize notification service for authenticated user
     */
    async initialize(config = {}) {
        const { userId, reminderTime, remindersEnabled } = config;

        if (!this.isSupported()) {
            console.warn('Notifications not supported in this browser');
            return;
        }

        // If reminders are enabled and we have a time
        if (remindersEnabled && reminderTime) {
            // Check if permission already granted
            if (Notification.permission === 'granted') {
                this.permissionGranted = true;
                this.scheduleReminder(reminderTime, userId);
            } else if (Notification.permission === 'default') {
                // Request permission on first login
                const granted = await this.requestPermission();
                if (granted) {
                    this.scheduleReminder(reminderTime, userId);
                }
            }
        }
    }
}

// Export singleton instance
const notificationService = new NotificationService();
export default notificationService;

// Make it available globally for inline scripts
window.notificationService = notificationService;
