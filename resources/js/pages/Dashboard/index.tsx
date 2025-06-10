import { useNavigate } from "react-router-dom";
import Card from "../../components/Card";

const Dashboard: React.FC = () => {
  const navigate = useNavigate();
  const handleNavigate = () => {
    navigate("/home");
  };

  return (
    <>
      <div>
        <h1>Páginal de Dash</h1>
      </div>
      <span onClick={handleNavigate}>Ir para a página inicial</span>
      <Card />

      
    </>
  );
};

export default Dashboard;