// Import AOS library
import AOS from 'aos';
import 'aos/dist/aos.css';

// Initialize AOS with custom settings
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,      // Animation duration
        easing: 'ease-out', // Easing function
        once: true,         // Animation happens only once
        mirror: false,      // Don't animate out while scrolling past elements
        offset: 100,        // Offset from the trigger point
        delay: 0,           // Global delay
        disable: false,     // Disable AOS for certain conditions
        startEvent: 'DOMContentLoaded', // Event on which AOS should initialize
        disableMutationObserver: false, // Disable automatic mutations detection
        debounceDelay: 50,  // Debounce delay on resize events
        throttleDelay: 99,  // Throttle delay on scroll events
    });

    console.log('AOS initialized successfully');
});
