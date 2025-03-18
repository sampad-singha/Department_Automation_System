import { useContext, useState } from 'react';
import { AuthContext } from '../../../Layout/AuthProvider/AuthProvider';
import { NavLink } from 'react-router-dom';
import { IoIosLogIn } from 'react-icons/io';
import { FiMenu, FiX } from 'react-icons/fi';

const Navbar = () => {
    const { user, logOut } = useContext(AuthContext);
    const [menuOpen, setMenuOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);

    const handleSignOut = () => {
        logOut().catch(err => console.error(err));
    };

    return (
        <nav className="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-4 shadow-lg sticky top-0 z-50">
            <div className="container mx-auto flex justify-between items-center">
                <NavLink to="/" className="text-2xl font-bold tracking-wide">Brand</NavLink>
                
                {/* Mobile Menu Toggle */}
                <button 
                    className="text-white md:hidden" 
                    onClick={() => setMenuOpen(!menuOpen)}
                >
                    {menuOpen ? <FiX size={28} /> : <FiMenu size={28} />}
                </button>

                {/* Center Menu Items */}
                <div className="hidden md:flex gap-6 items-center mx-auto">
                    <NavLink to="/" className="hover:text-primary">Home</NavLink>
                    <NavLink to="/courses" className="hover:text-primary">Courses</NavLink>
                    <NavLink to="/timetable" className="hover:text-primary">Timetable</NavLink>
                    <NavLink to="/attendance" className="hover:text-primary">Attendance</NavLink>
                    <NavLink to="/grades" className="hover:text-primary">Grades</NavLink>
                    <NavLink to="/fees" className="hover:text-primary">Fees</NavLink>
                    <NavLink to="/messages" className="hover:text-primary">Messages</NavLink>
                    <NavLink to="/library" className="hover:text-primary">Library</NavLink>
                    <NavLink to="/internships" className="hover:text-primary">Internships</NavLink>
                    <NavLink to="/requests" className="hover:text-primary">Requests</NavLink>
                    <NavLink to="/profile" className="hover:text-primary">Profile</NavLink>
                </div>

                {/* Right Side Profile & Login */}
                <div className="hidden md:flex items-center gap-4">
                    {user ? (
                        <div className="relative">
                            <button 
                                onClick={() => setProfileOpen(!profileOpen)} 
                                className="w-10 h-10 rounded-full overflow-hidden border-2 border-white"
                            >
                                <img 
                                    src={user.photoURL || 'https://i.ibb.co/qW320MT/images.jpg'}
                                    alt="User Avatar"
                                    className="w-full h-full object-cover"
                                />
                            </button>
                            {profileOpen && (
                                <div className="absolute right-0 mt-2 w-48 bg-gray-900 text-white shadow-lg rounded-md p-2">
                                    <NavLink to="/profile" className="block px-4 py-2 hover:bg-gray-700 rounded">My Profile</NavLink>
                                    <button 
                                        onClick={handleSignOut} 
                                        className="block w-full text-left px-4 py-2 text-red-400 hover:bg-gray-700 rounded"
                                    >
                                        Logout
                                    </button>
                                </div>
                            )}
                        </div>
                    ) : (
                        <NavLink to="/login" className="hover:text-primary flex items-center gap-1">
                            Login <IoIosLogIn className="text-xl" />
                        </NavLink>
                    )}
                </div>
            </div>

            {/* Mobile Menu */}
            {menuOpen && (
                <div className="md:hidden bg-gray-900 p-4 mt-2 rounded-lg flex flex-col items-center">
                    <NavLink to="/" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Home</NavLink>
                    <NavLink to="/courses" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Courses</NavLink>
                    <NavLink to="/timetable" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Timetable</NavLink>
                    <NavLink to="/attendance" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Attendance</NavLink>
                    <NavLink to="/grades" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Grades</NavLink>
                    <NavLink to="/fees" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Fees</NavLink>
                    <NavLink to="/messages" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Messages</NavLink>
                    <NavLink to="/library" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Library</NavLink>
                    <NavLink to="/internships" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Internships</NavLink>
                    <NavLink to="/requests" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Requests</NavLink>
                    <NavLink to="/profile" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Profile</NavLink>
                    {user ? (
                        <button 
                            onClick={() => {
                                handleSignOut();
                                setMenuOpen(false);
                            }} 
                            className="block py-2 text-red-400"
                        >
                            Logout
                        </button>
                    ) : (
                        <NavLink to="/login" className="py-2 flex items-center gap-1 w-full justify-center" onClick={() => setMenuOpen(false)}>
                            Login <IoIosLogIn className="text-xl" />
                        </NavLink>
                    )}
                </div>
            )}
        </nav>
    );
};

export default Navbar;
