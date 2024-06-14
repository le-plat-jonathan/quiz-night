export const setSession = (token) => {
  localStorage.setItem("jwtToken", token);
};

export const getSession = () => {
  return localStorage.getItem("jwtToken");
};

export const checkAuthLoader = () => {
  const token = getSession();
  if (!token) {
    return navigate("/auth/login");
  }
  return null;
};

export const getSessionRequired = () => {
  const token = getSession();
  if (!token) return false;
};
