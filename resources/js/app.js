import './bootstrap';

import Alpine from 'alpinejs';
import audioPlayer from './components/audioPlayer';
import exerciseAttempt from './components/exerciseAttempt';

window.Alpine = Alpine;

// Register Alpine.js components
Alpine.data('audioPlayer', audioPlayer);
Alpine.data('exerciseAttempt', exerciseAttempt);

Alpine.start();
