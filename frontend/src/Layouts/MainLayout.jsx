import Sidebar from "../Component/Share/Navbar/Sidebar.jsx";
import { Outlet, useNavigate } from "react-router-dom";
import { useAuth } from "../Contexts/AuthContext.jsx";

export default function MainLayout() {
    const { logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = () => {
        logout();
        navigate("/login");
    };

    return (
        <div className="flex">
            <Sidebar onLogout={handleLogout} />
            <div className="flex-1 ml-64 p-6">
                <Outlet />
            </div>
        </div>
    );
}
