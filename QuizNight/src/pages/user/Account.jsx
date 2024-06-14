import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { getSession } from "@/utils/auth";
import { client } from "@/utils/http";
import { useRef } from "react";
import { useMutation, useQuery } from "react-query";

export const AccountPage = () => {
  const session = getSession();
  if (!session) {
    throw new Error("L'utilisateur n'est pas connecter");
  }
  // const queryClient = useQueryClient()
  const passwordFormRef = useRef(null);

  const { isPending, isError, data, error } = useQuery({
    queryKey: ["profile"],
    queryFn: () =>
      client("http://localhost:8000/api/user", { session: session }),
  });

  const mutation = useMutation({
    mutationFn: (newPassword) =>
      client("http://localhost:8000/api/user", {
        data: newPassword,
        method: "PUT",
        session: session,
      }),
    onSuccess: () => {
      alert("Password changé");
      passwordFormRef.current.reset();
    },
  });

  const handleSubmitNewPassword = (e) => {
    e.preventDefault();

    const dataForm = new FormData(e.target);
    const newPassword = {
      password: dataForm.get("password"),
    };
    mutation.mutate(newPassword);
  };

  if (isPending) {
    return <span>Loading...</span>;
  }

  if (isError) {
    return <span>Error: {error.message}</span>;
  }

  if (!data) {
    return <span>No data available</span>;
  }

  return (
    <div>
      <div className="max-w-7xl grid grid-cols-1 px-4 py-16 gap-5 lg:px-8 md:grid-cols-3 sm:px-6">
        <div>
          <h2 className="leading-7 font-semibold">Personal Information</h2>
          <p>Use a permanent address where you can receive mail.</p>
        </div>
        <form action="" className="col-span-2">
          <div className="grid grid-cols-1 gap-4 sm:max-w-xl sm:grid-cols-6">
            <div className="col-span-full flex items-center gap-8">
              <Avatar className="h-20 w-20 rounded-lg">
                <AvatarImage src="https://github.com/shadcn.png" />
                <AvatarFallback>CN</AvatarFallback>
              </Avatar>
              <div>
                <Button>Change Avatar</Button>
                <p className="mt-2 text-sm">JPG, GIF or PNG. 1MB max.</p>
              </div>
            </div>
            <div className="col-span-3">
              <Label>Prenom</Label>
              <Input />
            </div>
            <div className="col-span-3">
              <Label>Nom</Label>
              <Input />
            </div>
            <div className="col-span-full">
              <Label>Email address</Label>
              <Input defaultValue={data.email} />
            </div>
            <div className="col-span-full">
              <Label>Username</Label>
              <Input defaultValue={data.username} />
            </div>
          </div>
          <div className="flex mt-4">
            <Button>Save</Button>
          </div>
        </form>
      </div>
      <Separator />
      <div className="max-w-7xl grid grid-cols-1 px-4 py-16 gap-5 lg:px-8 md:grid-cols-3 sm:px-6">
        <div>
          <h2 className="leading-7 font-semibold">Change password</h2>
          <p>Update your password associated with your account.</p>
        </div>
        <form
          ref={passwordFormRef}
          onSubmit={handleSubmitNewPassword}
          className="col-span-2"
        >
          <div className="grid grid-cols-1 gap-4 sm:max-w-xl sm:grid-cols-6">
            <div className="col-span-full">
              <Label>Current password</Label>
              <Input />
            </div>
            <div className="col-span-full">
              <Label>New password</Label>
              <Input />
            </div>
            <div className="col-span-full">
              <Label>Confirm password</Label>
              <Input type="text" name="password" />
            </div>
          </div>
          <div className="flex mt-4">
            <Button type="submit">Save</Button>
          </div>
        </form>
      </div>
    </div>
  );
};
