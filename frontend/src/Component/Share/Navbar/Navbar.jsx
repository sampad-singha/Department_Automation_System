import { useContext, useState } from 'react';
import { AuthContext } from '../../../Layout/AuthProvider/AuthProvider';
import { NavLink } from 'react-router-dom';
import { IoIosLogIn, IoIosLogOut } from 'react-icons/io';
import { FiMenu, FiX, FiChevronDown } from 'react-icons/fi';

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
                    <NavLink to="/about" className="hover:text-primary">About</NavLink>
                    <NavLink to="/services" className="hover:text-primary">Services</NavLink>
                    <NavLink to="/contact" className="hover:text-primary">Contact</NavLink>
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
                                    <NavLink to="/settings" className="block px-4 py-2 hover:bg-gray-700 rounded">Settings</NavLink>
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
                    <NavLink to="/about" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>About</NavLink>
                    <NavLink to="/services" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Services</NavLink>
                    <NavLink to="/contact" className="py-2 w-full text-center" onClick={() => setMenuOpen(false)}>Contact</NavLink>
                    {user ? (
                        <>
                            <button 
                                onClick={() => setProfileOpen(!profileOpen)} 
                                className="w-16 h-16 rounded-full overflow-hidden border-2 border-white my-4"
                            >
                                <img 
                                    src={user.photoURL || 'https://i.ibb.co/qW320MT/images.jpg'}
                                    alt="User Avatar"
                                    className="w-full h-full object-cover"
                                />
                            </button>
                            {profileOpen && (
                                <div className="bg-gray-800 text-white rounded-md p-2 w-full text-center">
                                    <NavLink to="/profile" className="block py-2" onClick={() => setMenuOpen(false)}>My Profile</NavLink>
                                    <NavLink to="/settings" className="block py-2" onClick={() => setMenuOpen(false)}>Settings</NavLink>
                                    <button 
                                        onClick={() => {
                                            handleSignOut();
                                            setMenuOpen(false);
                                        }} 
                                        className="block py-2 text-red-400"
                                    >
                                        Logout
                                    </button>
                                </div>
                            )}
                        </>
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