// src/context/AuthContext.js
import { createContext, useEffect, useState } from "react";
import { getSessionRequired, setSession } from "../utils/auth";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(getSessionRequired());
  const [authError, setAuthError] = useState(null);
  //   const navigate = useNavigate();

  useEffect(() => {
    setIsAuthenticated(getSessionRequired());
  }, []);

  const login = (token) => {
    try {
      setSession(token);
    } catch (error) {
      setAuthError(error.message);
    }
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, login, authError }}>
      {children}
    </AuthContext.Provider>
  );
};
