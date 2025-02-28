import { useContext, useState } from 'react';
import { Link, NavLink, Outlet } from 'react-router-dom';
import { AuthContext } from '../AuthProvider/AuthProvider';
import { Menu } from 'lucide-react';
import { FiChevronDown, FiChevronUp } from 'react-icons/fi';
import { motion } from "framer-motion";
const StudentDashBoard = () => {
    const [openSubMenu, setOpenSubMenu] = useState(null);
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const { user } = useContext(AuthContext);

    const toggleSubMenu = (key) => {
        setOpenSubMenu(openSubMenu === key ? null : key);
    };
    const menuItems = [
        { type: "link", name: "Dashboard", path: "/studentDashBoard" },
        { type: "link", name: "Messages", path: "/studentDashBoard/messages" },
        {
            type: "dropdown", name: "My Class", key: "my-class", items: [
                { name: "Class Schedule", path: "/studentDashBoard/schedule" },
                { name: "Assignments", path: "/my-class/assignments" },
                { name: "Grades", path: "/my-class/grades" },
                { name: "Discussions", path: "/my-class/discussions" },
                { name: "Attendance", path: "/my-class/attendance" },
            ]
        },
        { type: "link", name: "Notifications", path: "/notifications" },
        {
            type: "dropdown", name: "My Course", key: "my-course", items: [
                { name: "Enrolled Courses", path: "/my-course/enrolled" },
                { name: "Course Materials", path: "/my-course/materials" },
                { name: "Progress Report", path: "/my-course/progress" },
                { name: "Course Discussions", path: "/my-course/discussions" },
                { name: "Upcoming Exams", path: "/my-course/exams" },
            ]
        },
        {
            type: "dropdown", name: "Resources", key: "resources", items: [
                { name: "Library", path: "/resources/library" },
                { name: "Tutorials", path: "/resources/tutorials" },
                { name: "Webinars", path: "/resources/webinars" },
            ]
        },
        { type: "link", name: "My Result", path: "/studentDashBoard/result" },
        {
            type: "dropdown", name: "Support", key: "support", items: [
                { name: "FAQ", path: "/support/faq" },
                { name: "Contact Support", path: "/support/contact" },
                { name: "Report an Issue", path: "/support/report" },
            ]
        },
        { type: "link", name: "Settings", path: "/settings" },
        // { type: "link", name: "Logout", path: "/logout", specialClass: "bg-red-600 hover:bg-red-700" },
    ];

    return (
        <div className="">
            {/* Navbar (Fixed at top) */}
            <nav className="w-full bg-indigo-800 fixed top-0 z-20 text-white flex justify-between items-center shadow-md">
                <button
                    className="p-2 bg-indigo-600 text-white rounded-md shadow-md md:hidden"
                    onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                >
                    <Menu size={24} />
                </button>
                {/*  */}
                <div className="navbar bg-indigo-800 text-white">
                    <div className="flex-1">
                        <a href="/" className="btn btn-ghost text-xl">LOGO</a>
                    </div>
                    <div className="flex-none">
                        <div className="dropdown dropdown-end">
                            <div tabIndex={0} role="button" className="btn btn-ghost btn-circle">
                                <div className="indicator">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span className="badge badge-sm indicator-item">8</span>
                                </div>
                            </div>
                            <div
                                tabIndex={0}
                                className="card card-compact dropdown-content bg-gray-900 text-white z-[10] mt-3 w-72 shadow-xl rounded-lg">
                                <div className="card-body p-4">
                                    <div className="flex justify-between items-center border-b border-gray-700 pb-2">
                                        <span className="text-lg font-bold">📢 Notifications</span>
                                        <button className="text-sm text-gray-400 hover:text-white">Clear all</button>
                                    </div>

                                    {/* Notification Items */}
                                    <div className="mt-2 space-y-3">
                                        <button className="flex items-start p-3 bg-gray-800 hover:bg-gray-700 rounded-lg w-full text-left">
                                            <div className="mr-3 text-blue-400 text-xl">📅</div>
                                            <div className="flex-1">
                                                <span className="font-semibold">New Class Routine Updated</span>
                                                <p className="text-xs text-gray-400">Check the latest schedule for your courses.</p>
                                                <span className="text-xs text-gray-500">5 mins ago</span>
                                            </div>
                                        </button>

                                        <button className="flex items-start p-3 bg-gray-800 hover:bg-gray-700 rounded-lg w-full text-left">
                                            <div className="mr-3 text-green-400 text-xl">🎓</div>
                                            <div className="flex-1">
                                                <span className="font-semibold">Exam Results Published</span>
                                                <p className="text-xs text-gray-400">Your semester results are now available.</p>
                                                <span className="text-xs text-gray-500">1 hour ago</span>
                                            </div>
                                        </button>

                                        <button className="flex items-start p-3 bg-gray-800 hover:bg-gray-700 rounded-lg w-full text-left">
                                            <div className="mr-3 text-yellow-400 text-xl">⚠️</div>
                                            <div className="flex-1">
                                                <span className="font-semibold">Important Notice</span>
                                                <p className="text-xs text-gray-400">University will remain closed on Friday.</p>
                                                <span className="text-xs text-gray-500">Yesterday</span>
                                            </div>
                                        </button>
                                    </div>

                                    <div className="card-actions mt-4">
                                        <button className="btn btn-primary btn-block">View All</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div className="dropdown dropdown-end">
                            {/* <p>Hasan</p> */}
                            <div tabIndex={0} role="button" className="btn btn-ghost btn-circle avatar">
                                <div className="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-600">
                                    <img
                                        alt="User Profile"
                                        src={user.image}
                                    />
                                </div>
                            </div>
                            <ul
                                tabIndex={0}
                                className="menu menu-sm dropdown-content bg-gray-900 text-white rounded-lg z-[10] mt-3 w-56 p-2 shadow-lg">
                                <li>
                                    <a className="flex justify-between items-center hover:bg-gray-800 rounded-md p-2">
                                        Profile
                                        <span className="badge bg-blue-500 text-white">New</span>
                                    </a>
                                </li>
                                <li>
                                    <a className="hover:bg-gray-800 rounded-md p-2">Settings</a>
                                </li>
                                <li>
                                    <a className="hover:bg-gray-800 rounded-md p-2 text-red-400">Logout</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                {/*  */}
            </nav>

            <div className="flex flex-1 min-h-screen ">
                {/* Sidebar */}
                <div
                    className={`md:w-64 w-56 fixed top-0 left-0 h-screen bg-gradient-to-r from-indigo-700 via-indigo-800 to-indigo-900 text-white flex flex-col p-6 space-y-6 shadow-lg 
        transition-transform duration-300 ease-in-out overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-600 scrollbar-track-indigo-900
        ${isSidebarOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0"}`}
                >
                    {/* Sidebar Content */}
                    {/* Menu items */}
                    <ul className="space-y-4 pt-12">
                        {menuItems.map((item, index) => (
                            <li key={index}>
                                {item.type === "link" ? (
                                    <NavLink
                                        to={item.path}
                                        className={({ isActive }) =>
                                            `flex items-center p-3 rounded-md transition duration-300 ${isActive ? "bg-indigo-600" : "bg-indigo-800 hover:bg-indigo-700"
                                            } ${item.specialClass || ""}`
                                        }
                                    >
                                        {item.name}
                                    </NavLink>
                                ) : (
                                    <>
                                        <button
                                            onClick={() => toggleSubMenu(item.key)}
                                            className="flex justify-between items-center w-full p-3 rounded-md transition duration-300 bg-indigo-800 hover:bg-indigo-700"
                                        >
                                            {item.name} {openSubMenu === item.key ? <FiChevronUp /> : <FiChevronDown />}
                                        </button>
                                        <motion.div
                                            initial={{ height: 0, opacity: 0 }}
                                            animate={openSubMenu === item.key ? { height: "auto", opacity: 1 } : { height: 0, opacity: 0 }}
                                            transition={{ duration: 0.3, ease: "easeInOut" }}
                                            className="overflow-hidden"
                                        >
                                            <ul className="pl-4 space-y-2 bg-indigo-700 rounded-md mt-1 py-2">
                                                {item.items.map((subItem, subIndex) => (
                                                    <li key={subIndex}>
                                                        <NavLink
                                                            to={subItem.path}
                                                            className={({ isActive }) =>
                                                                `block p-2 rounded-md transition ${isActive ? "bg-indigo-600" : "bg-indigo-800 hover:bg-indigo-700"
                                                                }`
                                                            }
                                                        >
                                                            {subItem.name}
                                                        </NavLink>
                                                    </li>
                                                ))}
                                            </ul>
                                        </motion.div>
                                    </>
                                )}
                            </li>
                        ))}
                    </ul>
                </div>

                {/* Content Area */}
                <div className={`${isSidebarOpen ? "ml-56" : "ml-0"} pt-16 md:ml-64  flex-1 min-h-screen bg-gray-100 p-6 overflow-y-auto`}>
                    <Outlet />
                </div>
            </div>

        </div>
    );
};

export default StudentDashBoard;
