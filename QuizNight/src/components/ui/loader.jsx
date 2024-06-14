import { cn } from "@/lib/utils";
import { Loader2 } from "lucide-react";

export const Loader = ({ className, ...props }) => {
  return <Loader2 className={cn("animate-spin", className)} {...props} />;
};
