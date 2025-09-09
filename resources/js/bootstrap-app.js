// Import Bootstrap CSS
import '../css/bootstrap.scss';

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap';

// Make Bootstrap available globally for any custom scripts
window.bootstrap = bootstrap;

// You can also import individual components if you prefer smaller bundle size
// import { Tooltip, Toast, Popover } from 'bootstrap';

console.log('Bootstrap loaded successfully');
