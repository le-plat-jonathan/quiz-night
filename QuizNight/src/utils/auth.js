export const setSession = (user_id) => {
  localStorage.setItem("user_id", user_id);
};

export const getSession = () => {
  return localStorage.getItem("user_id");
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
