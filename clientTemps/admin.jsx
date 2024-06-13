import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Admin = () => {
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [quizzes, setQuizzes] = useState([]);

    useEffect(() => {
        const fetchQuizzes = async () => {
            try {
                const response = await axios.get('/get/quizzes');
                setQuizzes(response.data);
            } catch (error) {
                console.error(error);
            }
        };

        fetchQuizzes();
    }, []);

    const handleCreateQuiz = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('/post/quizzes', { title, description });
            setQuizzes([...quizzes, response.data]);
        } catch (error) {
            console.error(error);
        }
    };

    const handleDeleteQuiz = async (id) => {
        try {
            await axios.delete(`/delete/quizzes/${id}`);
            setQuizzes(quizzes.filter((quiz) => quiz.id !== id));
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <div>
            <h1>Admin Panel</h1>
            <form onSubmit={handleCreateQuiz}>
                <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} placeholder="Title" required />
                <textarea value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Description"></textarea>
                <button type="submit">Create Quiz</button>
            </form>
            <h2>All Quizzes</h2>
            <ul>
                {quizzes.map((quiz) => (
                    <li key={quiz.id}>
                        {quiz.title}
                        <button onClick={() => handleDeleteQuiz(quiz.id)}>Delete</button>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default Admin;
