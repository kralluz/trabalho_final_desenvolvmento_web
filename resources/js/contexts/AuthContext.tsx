import { createContext, ReactNode, useContext, useState } from "react";

type User = {
  email: string;
  password: string;
};

type UserData = {
  email: string;
  password: string;
};

type AuthContextType = {
  user: User | null;
  isAuthenticated: boolean;
  login: (userData: UserData) => void;
  logout: () => void;
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);

  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);

  const login = (UserData: User) => {
    setUser(UserData);
    setIsAuthenticated(true);
  };

  const logout = () => {
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, isAuthenticated, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error("useAuth precisa estar dentro de AuthProvider");
  }

  return context;
};