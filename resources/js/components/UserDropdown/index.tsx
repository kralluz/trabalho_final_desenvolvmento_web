import { useState, useRef, useEffect } from 'react';
import './UserDropdown.style.css';

interface UserDropdownProps {
  user: {
    name: string;
    email: string;
  };
  onLogout: () => void;
}

const UserDropdown: React.FC<UserDropdownProps> = ({ user, onLogout }) => {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  const getInitials = (name: string) => {
    return name
      .split(' ')
      .map(n => n[0])
      .join('')
      .toUpperCase()
      .slice(0, 2);
  };

  return (
    <div className="user-dropdown" ref={dropdownRef}>
      <div 
        className="user-avatar" 
        onClick={() => setIsOpen(!isOpen)}
        title={user.name}
      >
        <div className="avatar-circle">
          {getInitials(user.name)}
        </div>
      </div>
      
      {isOpen && (
        <div className="dropdown-menu">
          <div className="user-info">
            <div className="user-name">{user.name}</div>
            <div className="user-email">{user.email}</div>
          </div>
          <div className="dropdown-divider"></div>
          <button 
            className="dropdown-item logout-btn" 
            onClick={onLogout}
          >
            Sair
          </button>
        </div>
      )}
    </div>
  );
};

export default UserDropdown;
