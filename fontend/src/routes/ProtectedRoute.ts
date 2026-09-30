import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../hooks/useAuth"; 
import { ProtectedRouteProps } from "../types/types";


const ProtectedRoute = ({ children, roles }: ProtectedRouteProps) => {
  const { user, loading } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {

    if (loading) return;  
    if (!user) {
      navigate("/", { replace: true });
    } else if (roles && !user.roles?.some((role) => roles.includes(role))) {
       
      navigate("/unauthorized", { replace: true });
    }
  }, [user, roles, loading, navigate]); 

   
  if (loading || !user || (roles && !user.roles?.some((role) => roles.includes(role)))) {
    return null;
  }

  return children;
};

export default ProtectedRoute;
