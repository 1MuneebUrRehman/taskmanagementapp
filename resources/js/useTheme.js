// Simple theme management composable for Vue 3 (Inertia)
// Persists theme in localStorage and toggles the 'dark' class on <html>

export function getSystemPrefersDark() {
  if (typeof window === 'undefined') return false;
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

export function getStoredTheme() {
  if (typeof window === 'undefined') return null;
  try {
    return localStorage.getItem('theme');
  } catch (e) {
    return null;
  }
}

export function applyTheme(theme) {
  if (typeof document === 'undefined') return;
  const root = document.documentElement;
  if (theme === 'dark') {
    root.classList.add('dark');
  } else {
    root.classList.remove('dark');
  }
}

export function setTheme(theme) {
  try {
    localStorage.setItem('theme', theme);
  } catch (e) {
    // ignore
  }
  applyTheme(theme);
}

export function currentTheme() {
  const stored = getStoredTheme();
  if (stored === 'dark' || stored === 'light') return stored;
  return getSystemPrefersDark() ? 'dark' : 'light';
}

export function toggleTheme() {
  const next = currentTheme() === 'dark' ? 'light' : 'dark';
  setTheme(next);
  return next;
}
