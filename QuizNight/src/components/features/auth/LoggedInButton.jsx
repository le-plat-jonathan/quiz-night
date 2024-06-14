import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Separator } from "@/components/ui/separator";
import { LogOut, User2 } from "lucide-react";
import { Link } from "react-router-dom";

export const LoggedInButton = (props) => {
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="sm">
          <Avatar className="mr-2 size-6">
            <AvatarFallback>{props.user?.name?.[0]}</AvatarFallback>
            {props.user.image && (
              <AvatarImage
                src={props.user.image}
                alt={props.user.name ?? "user picture"}
              />
            )}
          </Avatar>
          {props.user.name}
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent>
        <DropdownMenuItem asChild>
          <Link to="user/account">
            <User2 className="mr-2" size={12} />
            Profile
          </Link>
        </DropdownMenuItem>
        <Separator />

        <DropdownMenuItem className="focus:bg-primary focus:text-primary-foreground">
          <LogOut className="mr-2" size={12} />
          Logout
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
};
