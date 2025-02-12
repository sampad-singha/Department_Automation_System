import { useContext, useState } from 'react';
import { AuthContext } from '../../../Layout/AuthProvider/AuthProvider';
import { NavLink } from 'react-router-dom';
import { IoIosLogIn, IoIosLogOut } from 'react-icons/io';
import { FiMenu, FiX } from 'react-icons/fi';

const Navbar = () => {
    const { user, logOut } = useContext(AuthContext);
    const [menuOpen, setMenuOpen] = useState(false);
    
    const handleSignOut = () => {
        logOut().catch(err => console.error(err));
    };

    return (
        <nav className="bg-gray-800 text-white p-4 shadow-md sticky top-0 z-50">
            <div className="container mx-auto flex justify-between items-center">
                <NavLink to="/" className="text-xl font-bold">Brand</NavLink>

                {/* Mobile Menu Toggle */}
                <button 
                    className="text-white md:hidden" 
                    onClick={() => setMenuOpen(!menuOpen)}
                >
                    {menuOpen ? <FiX size={24} /> : <FiMenu size={24} />}
                </button>

                {/* Desktop Menu */}
                <div className="hidden md:flex gap-6">
                    <NavLink to="/about" className="hover:text-primary">About</NavLink>
                    {user ? (
                        <>
                            <NavLink to="/dashboard" className="hover:text-primary">Dashboard</NavLink>
                            <button 
                                onClick={handleSignOut} 
                                className="flex items-center gap-2 text-red-400 hover:text-red-300"
                            >
                                Logout <IoIosLogOut />
                            </button>
                            <div className="w-10 h-10 rounded-full overflow-hidden border-2 border-white">
                                <img 
                                    src={user.photoURL ? user.photoURL : 'https://i.ibb.co/qW320MT/images.jpg'}
                                    alt="User Avatar"
                                    className="w-full h-full object-cover"
                                />
                            </div>
                        </>
                    ) : (
                        <>
                            <NavLink to="/login" className="hover:text-primary flex items-center gap-1">
                                Login <IoIosLogIn className="text-xl" />
                            </NavLink>
                            <NavLink to="/register" className="hover:text-primary flex items-center gap-1">
                                Sign Up <IoIosLogIn className="text-xl" />
                            </NavLink>
                        </>
                    )}
                </div>
            </div>

            {/* Mobile Menu */}
            {menuOpen && (
                <div className="md:hidden flex flex-col bg-gray-900 p-4 mt-2 rounded-lg">
                    <NavLink to="/about" className="py-2" onClick={() => setMenuOpen(false)}>About</NavLink>
                    {user ? (
                        <>
                            <NavLink to="/dashboard" className="py-2" onClick={() => setMenuOpen(false)}>Dashboard</NavLink>
                            <button 
                                onClick={() => {
                                    handleSignOut();
                                    setMenuOpen(false);
                                }} 
                                className="py-2 text-red-400"
                            >
                                Logout
                            </button>
                            <div className="w-16 h-16 rounded-full overflow-hidden border-2 border-white mx-auto mt-4">
                                <img 
                                    src={user.photoURL ? user.photoURL : 'https://i.ibb.co/qW320MT/images.jpg'}
                                    alt="User Avatar"
                                    className="w-full h-full object-cover"
                                />
                            </div>
                        </>
                    ) : (
                        <>
                            <NavLink to="/login" className="py-2 flex items-center gap-1" onClick={() => setMenuOpen(false)}>
                                Login <IoIosLogIn className="text-xl" />
                            </NavLink>
                            <NavLink to="/signUp" className="py-2 flex items-center gap-1" onClick={() => setMenuOpen(false)}>
                                Sign Up <IoIosLogIn className="text-xl" />
                            </NavLink>
                        </>
                    )}
                </div>
            )}
        </nav>
    );
};

export default Navbar;
