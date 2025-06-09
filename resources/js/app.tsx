import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import App from './components/App';

// Initialize theme
initializeTheme();

// Get the root element
const container = document.getElementById('app');

if (container) {
    const root = createRoot(container);
    root.render(<App />);
} else {
    console.error('Root element #app not found');
}
