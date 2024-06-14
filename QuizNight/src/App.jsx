import { QueryClient, QueryClientProvider } from "react-query";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { ThemeProvider } from "./components/theme-provider";
import { AuthProvider } from "./context/auth.context";
import { ErrorPage } from "./pages/Error";
import { HomePage } from "./pages/Home";
import { LoginFormPage } from "./pages/LoginForm";
import QuizPage from "./pages/Quiz";
import { RootLayout } from "./pages/Root";
import { SignupForm } from "./pages/SignupForm";
import { AccountPage } from "./pages/user/Account";
import { DashboardPage } from "./pages/user/Dashboard";
import QuizFormPage from "./pages/user/QuizForm";
import { RootUserLayout } from "./pages/user/RootUser";

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 90000,
      retry: false,
      refetchOnWindowFocus: false,
    },
  },
});

const router = createBrowserRouter([
  {
    path: "/",
    element: <RootLayout />,
    errorElement: <ErrorPage />,
    children: [
      { path: "/", element: <HomePage /> },
      { path: "/quiz", element: <QuizPage /> },
      { path: "/auth/login", element: <LoginFormPage /> },
      { path: "/auth/signup", element: <SignupForm /> },
    ],
  },
  {
    path: "/user",
    element: <RootUserLayout />,
    errorElement: <ErrorPage />,
    // loader: checkAuthLoader,
    children: [
      { path: "", element: <DashboardPage /> },
      { path: "create-quiz", element: <QuizFormPage /> },
      { path: "account", element: <AccountPage /> },
    ],
  },
]);

export const App = () => {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <ThemeProvider
          attribute="class"
          defaultTheme="dark"
          enableSystem
          disableTransitionOnChange
        >
          <RouterProvider router={router}></RouterProvider>;
        </ThemeProvider>
      </AuthProvider>
    </QueryClientProvider>
  );
};
