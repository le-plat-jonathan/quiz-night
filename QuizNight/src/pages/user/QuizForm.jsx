import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { useFieldArray, useForm } from "react-hook-form";

const QuizFormPage = () => {
  const { register, control, handleSubmit, reset, watch } = useForm({
    defaultValues: {
      quizTitle: "",
      questions: [
        {
          questionText: "",
          answers: [
            { answerText: "", isCorrect: false },
            { answerText: "", isCorrect: false },
            { answerText: "", isCorrect: false },
            { answerText: "", isCorrect: false },
          ],
        },
      ],
    },
  });

  const { fields, append, remove } = useFieldArray({
    control,
    name: "questions",
  });

  const onSubmit = (data) => {
    console.log(data);
    // Envoyer les données du quiz ou les traiter ici
    reset(); // Réinitialiser le formulaire après soumission
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="flex flex-col gap-4">
      <h1>Créer un Quiz</h1>

      <div>
        <label htmlFor="quizTitle">Titre du Quiz</label>
        <Input id="quizTitle" {...register("quizTitle", { required: true })} />
      </div>

      {fields.map((item, index) => (
        <div key={item.id} className="flex flex-col gap-4">
          <h2>Question {index + 1}</h2>
          <div>
            <label htmlFor={`questions[${index}].questionText`}>
              Texte de la Question
            </label>
            <Input
              id={`questions[${index}].questionText`}
              {...register(`questions[${index}].questionText`, {
                required: true,
              })}
            />
          </div>

          {item.answers.map((answer, ansIndex) => (
            <div key={ansIndex}>
              <label
                htmlFor={`questions[${index}].answers[${ansIndex}].answerText`}
              >
                Réponse {ansIndex + 1}
              </label>
              <Input
                id={`questions[${index}].answers[${ansIndex}].answerText`}
                {...register(
                  `questions[${index}].answers[${ansIndex}].answerText`,
                  { required: true }
                )}
              />
              <label
                htmlFor={`questions[${index}].answers[${ansIndex}].isCorrect`}
              >
                Correcte?
              </label>
              <Checkbox
                className="ml-4"
                id={`questions[${index}].answers[${ansIndex}].isCorrect`}
                {...register(
                  `questions[${index}].answers[${ansIndex}].isCorrect`
                )}
              />
            </div>
          ))}

          <Button
            variant="destructive"
            type="button"
            onClick={() => remove(index)}
          >
            Supprimer la question
          </Button>
        </div>
      ))}

      <Button
        type="button"
        onClick={() =>
          append({
            questionText: "",
            answers: [
              { answerText: "", isCorrect: false },
              { answerText: "", isCorrect: false },
              { answerText: "", isCorrect: false },
              { answerText: "", isCorrect: false },
            ],
          })
        }
      >
        Ajouter une question
      </Button>

      <Button type="submit">Créer le Quiz</Button>
    </form>
  );
};

export default QuizFormPage;
