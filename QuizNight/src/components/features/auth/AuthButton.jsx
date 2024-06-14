import { getSession } from "@/utils/auth";
import { LoggedInButton } from "./LoggedInButton";
import { LoginButton } from "./LoginButton";

export const AuthButton = () => {
  const user = getSession();

  if (!user) {
    return <LoginButton />;
  }

  return <LoggedInButton user={user} />;
};
