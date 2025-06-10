import { useNavigate } from "react-router-dom";

const NotFound: React.FC = () => {
  
  const navigate = useNavigate();
  const handleNavigate = () => {
    navigate("/login");
  };
    return (
    <>
      <div>
        <h1>Página não encontrada</h1>
        <span onClick={handleNavigate}>Tela Inicial</span>
      </div>
    </>
  );
};

export default NotFound;
