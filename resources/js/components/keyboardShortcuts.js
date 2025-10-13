/**
 * Global Keyboard Shortcuts Handler
 * 
 * Manages application-wide keyboard shortcuts and navigation
 */

export default function keyboardShortcuts() {
    return {
        showHelp: false,
        shortcuts: [
            {
                category: 'Global',
                items: [
                    { keys: ['Ctrl', 'K'], mac: ['⌘', 'K'], description: 'Open search', action: 'search' },
                    { keys: ['?'], description: 'Show keyboard shortcuts', action: 'help' },
                    { keys: ['Esc'], description: 'Close modals/dialogs', action: 'escape' },
                ]
            },
            {
                category: 'Flashcard Review',
                items: [
                    { keys: ['Space'], description: 'Flip card', action: 'flip' },
                    { keys: ['1'], description: 'Rate: Again', action: 'rate-1' },
                    { keys: ['2'], description: 'Rate: Hard', action: 'rate-2' },
                    { keys: ['3'], description: 'Rate: Good', action: 'rate-3' },
                    { keys: ['4'], description: 'Rate: Easy', action: 'rate-4' },
                ]
            },
            {
                category: 'Audio Playback',
                items: [
                    { keys: ['Space'], description: 'Play/Pause audio', action: 'audio-toggle' },
                    { keys: ['←', '→'], description: 'Adjust speed', action: 'audio-speed' },
                ]
            },
            {
                category: 'Navigation',
                items: [
                    { keys: ['Tab'], description: 'Navigate forward', action: 'tab-forward' },
                    { keys: ['Shift', 'Tab'], description: 'Navigate backward', action: 'tab-backward' },
                ]
            }
        ],

        init() {
            this.setupGlobalShortcuts();
        },

        setupGlobalShortcuts() {
            document.addEventListener('keydown', (event) => {
                // Ignore if user is typing in an input/textarea
                const activeElement = document.activeElement;
                const isTyping = activeElement && (
                    activeElement.tagName === 'INPUT' ||
                    activeElement.tagName === 'TEXTAREA' ||
                    activeElement.isContentEditable
                );

                // Ctrl/Cmd + K for search
                if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                    event.preventDefault();
                    this.focusSearch();
                    return;
                }

                // Shift + ? for help (only when not typing)
                if (!isTyping && event.key === '?' && event.shiftKey) {
                    event.preventDefault();
                    this.toggleHelp();
                    return;
                }

                // Escape to close modals
                if (event.key === 'Escape') {
                    this.handleEscape();
                    return;
                }
            });
        },

        focusSearch() {
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        },

        toggleHelp() {
            this.showHelp = !this.showHelp;
        },

        handleEscape() {
            // Close help modal if open
            if (this.showHelp) {
                this.showHelp = false;
                return;
            }

            // Blur active element
            if (document.activeElement) {
                document.activeElement.blur();
            }
        },

        getKeyDisplay(keys, isMac = false) {
            if (isMac && keys.mac) {
                return keys.mac.join(' + ');
            }
            return keys.keys.join(' + ');
        },

        isMac() {
            return navigator.platform.toUpperCase().indexOf('MAC') >= 0;
        }
    };
}

// Make it available globally for Alpine.js
if (typeof window !== 'undefined') {
    window.keyboardShortcuts = keyboardShortcuts;
}
