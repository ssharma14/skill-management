import React, { useState, useEffect } from 'react';
import SearchInput from './SearchInput';

export default function App({ userId }) {
  const [selected, setSelected] = useState([]);
  const [loading, setLoading] = useState(true);

  // Load saved skills on mount
  useEffect(() => {
    const loadSavedSkills = async () => {
      try {
        const response = await fetch(`/api/user-skills?user_id=${userId}`);
        if (response.ok) {
          const skills = await response.json();
          // Add showExperience: false to all loaded skills
          const loadedSkills = skills.map(s => ({
            ...s,
            showExperience: false
          }));
          setSelected(loadedSkills);
        }
      } catch (error) {
        console.error('Error loading saved skills:', error);
      } finally {
        setLoading(false);
      }
    };

    loadSavedSkills();
  }, [userId]);

  const addSkill = (skill) => {
    if (selected.find(s => s.id === skill.id)) return;
    setSelected([...selected, {
      ...skill,
      experience: '',
      unit: 'years',
      showExperience: false,
      saved: false
    }]);
  };

  const removeSkill = (id) => setSelected(selected.filter(s => s.id !== id));

  const toggleExperience = (id) => {
    setSelected(selected.map(s =>
      s.id === id ? { ...s, showExperience: !s.showExperience, saved: false } : s
    ));
  };

  const calculateLevel = (experience, unit) => {
    // Convert to months for consistent calculation
    const months = unit === 'years' ? experience * 12 : experience;

    if (months < 12) return 'beginner';
    if (months < 36) return 'intermediate';
    if (months < 60) return 'advanced';
    return 'expert';
  };

  const updateExperience = (id, field, value) => {
    setSelected(selected.map(s => {
      if (s.id === id) {
        const updated = { ...s, [field]: value, saved: false };
        // Recalculate level when experience or unit changes
        if (updated.experience && updated.experience > 0) {
          updated.level = calculateLevel(parseFloat(updated.experience), updated.unit);
        }
        return updated;
      }
      return s;
    }));
  };

  const showToast = (message, type = 'success') => {
    const notification = document.createElement('div');
    notification.className = `notification ${type === 'error' ? 'notification-error' : ''}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
  };

  const saveSkill = async (skill) => {
    // Validate experience
    if (!skill.experience || skill.experience === '' || skill.experience < 0) {
      showToast('Please enter a valid experience value', 'error');
      return;
    }

    const payload = {
      user_id: userId,
      skills: [{
        id: skill.id,
        experience: parseInt(skill.experience, 10),
        unit: skill.unit,
        level: skill.level
      }]
    };

    console.log('Saving skill:', payload);

    try {
      const response = await fetch('/api/user-skills/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      console.log('Response status:', response.status);
      const result = await response.json();
      console.log('Response data:', result);

      if (response.ok) {
        // Mark skill as saved and hide experience input
        setSelected(selected.map(s =>
          s.id === skill.id ? { ...s, saved: true, showExperience: false } : s
        ));

        showToast(`${skill.name} saved successfully!`);
      } else {
        console.error('Save failed:', result);
        showToast(`Failed to save: ${result.error || 'Unknown error'}`, 'error');
      }
    } catch (error) {
      console.error('Error saving skill:', error);
      showToast(`Failed to save skill: ${error.message}`, 'error');
    }
  };

  if (loading) {
    return (
      <div className="skill-selector">
        <div className="empty-state">Loading your skills...</div>
      </div>
    );
  }

  return (
    <div className="skill-selector">
      <SearchInput onSelect={addSkill} />
      <div className="selected-list">
        {selected.length === 0 ? (
          <div className="empty-state">
            No skills selected yet. Start typing to search and add skills!
          </div>
        ) : (
          selected.map(s => (
            <div key={s.id} className="card">
              <div className="card-header">
                <div className="card-info">
                  <div className="card-name">
                    {s.name}
                    {s.category && <span className="card-category"> • {s.category}</span>}
                    {s.level && <span className="card-level"> • {s.level}</span>}
                  </div>
                  {s.saved && (
                    <div className="saved-experience-text">
                      Experience: <strong>{s.experience} {s.unit}</strong>
                    </div>
                  )}
                </div>
                <a
                  href="#"
                  style={{
                    color: '#dc2626',
                    fontWeight: '500',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '4px',
                    textDecoration: 'none',
                    cursor: 'pointer'
                  }}
                  onMouseEnter={(e) => e.target.style.color = '#991b1b'}
                  onMouseLeave={(e) => e.target.style.color = '#dc2626'}
                  onClick={(e) => { e.preventDefault(); removeSkill(s.id); }}
                >
                  <span style={{ fontSize: '20px' }}>+</span> Remove
                </a>
              </div>

              {!s.showExperience ? (
                <a
                  href="#"
                  style={{
                    color: '#dc2626',
                    fontWeight: '500',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '4px',
                    textDecoration: 'none',
                    cursor: 'pointer'
                  }}
                  onMouseEnter={(e) => e.target.style.color = '#991b1b'}
                  onMouseLeave={(e) => e.target.style.color = '#dc2626'}
                  onClick={(e) => { e.preventDefault(); toggleExperience(s.id); }}
                >
                  <span style={{ fontSize: '20px' }}>+</span> {s.saved ? 'Edit Experience' : 'Add Experience'}
                </a>
              ) : (
                <div className="card-controls">
                  <label>Experience:</label>
                  <input
                    type="number"
                    min="0"
                    placeholder="Enter experience"
                    value={s.experience}
                    onChange={e => updateExperience(s.id, 'experience', e.target.value)}
                  />
                  <select
                    value={s.unit}
                    onChange={e => updateExperience(s.id, 'unit', e.target.value)}
                  >
                    <option value="years">years</option>
                    <option value="months">months</option>
                  </select>
                  <button
                    className="save-experience-btn"
                    onClick={() => saveSkill(s)}
                  >
                    Save
                  </button>
                  <button
                    className="cancel-experience-btn"
                    onClick={() => toggleExperience(s.id)}
                  >
                    Cancel
                  </button>
                </div>
              )}
            </div>
          ))
        )}
      </div>
    </div>
  );
}
