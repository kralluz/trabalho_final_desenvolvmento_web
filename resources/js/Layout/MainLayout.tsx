import { Outlet } from "react-router-dom";

const MainLayout = () => {
  return (
    <main className="mainLayout">
      <Outlet />
    </main>
  );
};

export default MainLayout;
