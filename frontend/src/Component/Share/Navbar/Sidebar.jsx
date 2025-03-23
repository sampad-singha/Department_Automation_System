import { Link, useLocation } from "react-router-dom";
import {
    HomeIcon,
    BellIcon,
    BookOpenIcon,
    ChartBarIcon,
    CreditCardIcon,
    UserGroupIcon,
    ClipboardDocumentIcon,
    ArrowRightStartOnRectangleIcon,
} from "@heroicons/react/24/outline";
import { useAuth } from "../../../Contexts/AuthContext.jsx";

const Sidebar = ({ onLogout }) => {
    const { user } = useAuth();
    const role = user?.roles?.[0]?.name?.toLowerCase(); // Get user role safely
    const location = useLocation(); // Get current route

    return (
        <div className="fixed top-0 left-0 flex flex-col w-64 h-screen p-5 text-white bg-gray-900">
            <h2 className="mb-6 text-2xl font-bold">Portal</h2>

            <nav className="flex-1 space-y-3">
                {/* Student Menu */}
                {role === "student" && (
                    <>
                        <SidebarLink to="/student/dashboard" icon={HomeIcon} text="Dashboard" active={location.pathname.startsWith("/student/dashboard")} />
                        <SidebarLink to="/student/courses" icon={BookOpenIcon} text="Courses" active={location.pathname.startsWith("/student/courses")} />
                        <SidebarLink to="/student/results" icon={ChartBarIcon} text="Results" active={location.pathname.startsWith("/student/results")} />
                        <SidebarLink to="/student/payments" icon={CreditCardIcon} text="Payments" active={location.pathname.startsWith("/student/payments")} />
                        <SidebarLink to="/student/notices" icon={BellIcon} text="Notices" active={location.pathname.startsWith("/student/notices")} />
                    </>
                )}

                {/* Teacher Menu */}
                {role === "teacher" && (
                    <>
                        <SidebarLink to="/teacher/dashboard" icon={HomeIcon} text="Dashboard" active={location.pathname.startsWith("/teacher/dashboard")} />
                        <SidebarLink to="/teacher/manage-students" icon={UserGroupIcon} text="Manage Students" active={location.pathname.startsWith("/teacher/manage-students")} />
                        <SidebarLink to="/teacher/grade-assignments" icon={ClipboardDocumentIcon} text="Grade Assignments" active={location.pathname.startsWith("/teacher/grade-assignments")} />
                    </>
                )}
            </nav>

            <button type="button" onClick={onLogout} className="flex items-center mt-auto text-red-400 hover:text-red-500">
                <ArrowRightStartOnRectangleIcon className="w-5 h-5 mr-2" />
                Logout
            </button>
        </div>
    );
};

// Sidebar Link Component with Active Highlighting
const SidebarLink = ({ to, icon: Icon, text, active }) => {
    return (
        <Link
            to={to}
            className={`flex items-center space-x-3 py-2 px-3 rounded-md transition ${
                active ? "bg-gray-300 text-gray-800 font-semibold" : "text-gray-300 hover:bg-gray-800"
            }`}
        >
            <Icon className="w-5 h-5" />
            <span>{text}</span>
        </Link>
    );
};

export default Sidebar;
