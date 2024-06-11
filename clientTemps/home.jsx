import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Home = () => {
    const [quizzes, setQuizzes] = useState([]);

    useEffect(() => {
        const fetchQuizzes = async () => {
            try {
                const response = await axios.get('/api/quizzes');
                setQuizzes(response.data);
            } catch (error) {
                console.error(error);
            }
        };

        fetchQuizzes();
    }, []);

    return (
        <div>
            <h1>All Quizzes</h1>
            <ul>
                {quizzes.map((quiz) => (
                    <li key={quiz.id}>{quiz.title}</li>
                ))}
            </ul>
        </div>
    );
};

export default Home;
