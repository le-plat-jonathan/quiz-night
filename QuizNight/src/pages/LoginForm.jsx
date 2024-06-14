import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { AuthContext } from "@/context/auth.context";
import { client } from "@/utils/http";
import { useContext } from "react";
import { useMutation } from "react-query";
import { Link, useNavigate } from "react-router-dom";

export const LoginFormPage = () => {
  const navigate = useNavigate();
  const { login, authError } = useContext(AuthContext);
  const mutation = useMutation(
    (user) =>
      client("http://localhost:8000/api/connexion", {
        method: "POST",
        data: user,
      }),
    {
      onSuccess: (data) => {
        login(data.token);
        if (!authError) {
          navigate("/user");
        }
      },
      onError: () => {
        alert("Mot de passe ou email incorect");
        navigate("/auth/login");
      },
    }
  );

  const handleSubmit = (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const newUser = {
      email: formData.get("email"),
      password: formData.get("password"),
    };
    mutation.mutate(newUser);
  };

  return (
    <div className="h-screen flex justify-center items-center">
      <Card className="mx-auto max-w-sm">
        <CardHeader>
          <CardTitle className="text-2xl">Login</CardTitle>
          <CardDescription>
            Enter your email below to login to your account
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="grid gap-4">
            <div className="grid gap-2">
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                type="email"
                name="email"
                placeholder="m@example.com"
                required
              />
            </div>
            <div className="grid gap-2">
              <div className="flex items-center">
                <Label htmlFor="password">Password</Label>
                <Link
                  href="#"
                  className="ml-auto inline-block text-sm underline"
                >
                  Forgot your password?
                </Link>
              </div>
              <Input name="password" id="password" type="password" required />
            </div>
            <Button type="submit" className="w-full">
              Login
            </Button>
            <Button variant="outline" className="w-full">
              Login with Google
            </Button>
          </form>
          <div className="mt-4 text-center text-sm">
            Don&apos;t have an account?{" "}
            <Link to="/auth/signup" className="underline">
              Sign up
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};
