"use client";

import { buttonVariants } from "@/components/ui/button";
import { Loader } from "@/components/ui/loader";
import { LogIn } from "lucide-react";
import { useMutation } from "react-query";
import { Link } from "react-router-dom";

export const LoginButton = () => {
  const mutation = useMutation({
    mutationFn: async () => () => null,
  });

  return (
    <Link
      className={buttonVariants({ variant: "outline", size: "sm" })}
      to="/auth/login"
      onClick={() => {
        mutation.mutate();
      }}
      disabled={mutation.isPending}
    >
      {mutation.isPending ? (
        <Loader className="mr-2" size={12} />
      ) : (
        <LogIn className="mr-2" size={12} />
      )}
      Login
    </Link>
  );
};
