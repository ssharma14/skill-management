import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './App';

const rootEl = document.getElementById('skill-selector-root');
if (rootEl) {
  const userId = rootEl.dataset.userId || null;
  const root = createRoot(rootEl);
  root.render(<App userId={userId} />);
}
