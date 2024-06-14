import { Faq } from "@/components/home/Faq";
import { Hero } from "@/components/home/Hero";
import { QuizCategorie } from "@/components/quiz/QuizCategorie";

export const HomePage = () => {
  return (
    <>
      <Hero />
      <QuizCategorie />
      <Faq />
    </>
  );
};
