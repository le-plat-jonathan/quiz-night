import { buttonVariants } from "@/components/ui/button";
import { Link } from "react-router-dom";

export const DashboardPage = () => {
  return (
    <>
      <div>
        <div className="flex items-center justify-between">
          <h2>Mes Quizz:</h2>
          <Link
            className={buttonVariants({ variant: "default" })}
            to={"create-quiz"}
          >
            Ajouter un quiz
          </Link>
        </div>
        <div></div>
      </div>
    </>
  );
};
