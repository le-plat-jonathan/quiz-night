import { Link } from "react-router-dom";

export const QuizCategorie = () => {
  const categories = [
    {
      name: "Technology",
      id: 1,
    },
    {
      name: "Nature",
      id: 2,
    },
    {
      name: "Politics",
      id: 3,
    },
    {
      name: "Sports",
      id: 4,
    },
  ];

  return (
    <div>
      <h2 className="text-center text-3xl font-semibold mb-10">
        Quiz Disponible
      </h2>
      <div className=" mx-auto  max-w-screen-lg w-full grid grid-cols-4 gap-4">
        {categories.length > 0 ? (
          categories.map((x, i) => {
            return (
              <Link
                key={i}
                to="/quiz"
                className="flex flex-col border hover:border-gray-200 justify-center rounded p-3 items-center bg-primary"
              >
                {x.name}
              </Link>
            );
          })
        ) : (
          <p>Pas de quiz disponible</p>
        )}
      </div>
    </div>
  );
};
