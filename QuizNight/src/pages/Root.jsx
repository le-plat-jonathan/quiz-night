import { Footer } from "@/components/layout/footer";
import { Header } from "@/components/layout/header";
import { Outlet } from "react-router";

export const RootLayout = () => {
  return (
    <>
      <Header />
      <main>
        <Outlet />
      </main>
      <Footer />
    </>
  );
};
