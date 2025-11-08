import React, { useState, useEffect, useRef } from 'react';

export default function SearchInput({ onSelect }) {
  const [q, setQ] = useState('');
  const [results, setResults] = useState([]);
  const [focused, setFocused] = useState(0);
  const listRef = useRef(null);

  useEffect(() => {
    const handler = setTimeout(() => {
      if (!q) {
        setResults([]);
        return;
      }
      fetch(`/api/skills?search=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(setResults);
    }, 250);
    return () => clearTimeout(handler);
  }, [q]);

  const handleSelect = (skill) => {
    onSelect(skill);
    setQ('');
    setResults([]);
    setFocused(0);
  };

  const keyDown = (e) => {
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setFocused((f) => Math.min(f + 1, results.length - 1));
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setFocused((f) => Math.max(f - 1, 0));
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if (results[focused]) {
        handleSelect(results[focused]);
      }
    }
  };

  return (
    <div className="search-input">
      <input value={q} onChange={e => setQ(e.target.value)} onKeyDown={keyDown} placeholder="Search skills (e.g., JavaScript, React, Python...)" />
      <ul ref={listRef} className="results">
        {results.map((r, i) => (
          <li key={r.id} className={i === focused ? 'focused' : ''} onMouseEnter={() => setFocused(i)} onClick={() => handleSelect(r)}>
            {r.name} <small>{r.category}</small>
          </li>
        ))}
      </ul>
    </div>
  );
}
