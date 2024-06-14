import ImageQuiz from "../../assets/quiz.png";
import TypingAnimation from "../magicui/typing-animation";
import { buttonVariants } from "../ui/button";

export const Hero = () => {
  return (
    <section
      className="bg-background mx-auto max-w-screen-lg px-4 py-20 lg:flex lg:items-center"
      id="home"
    >
      <div className="grid grid-cols-2 items-center max-lg:grid-cols-1">
        <div>
          <TypingAnimation text="Découvrez les Quiz Ultime" duration={100} />

          <p className="mt-4 sm:text-xl/relaxed">
            Testez vos connaissances et amusez-vous avec des milliers de
            questions captivantes! Relevez des défis, apprenez de nouvelles
            choses et mesurez-vous à vos amis pour voir qui est le véritable
            champion du quiz.
          </p>

          <div className="mt-8 flex flex-wrap  gap-4">
            <a
              className={buttonVariants({ variant: "default", size: "lg" })}
              href="#"
            >
              Get Started
            </a>

            <a
              className={buttonVariants({ variant: "outline", size: "lg" })}
              href="#"
            >
              Learn More
            </a>
          </div>
        </div>
        <div className="flex justify-end">
          <img className="w-96" src={ImageQuiz} alt="quiz night" />
        </div>
      </div>
    </section>
  );
};
