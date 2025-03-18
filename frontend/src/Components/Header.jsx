import { Link } from "react-router-dom";

const Header = () => {
  return (
    <header className="p-4 text-white bg-blue-600">
      <nav className="flex items-center justify-between">
        <h1 className="text-xl font-bold">My Department</h1>
        <ul className="flex space-x-4">
          <li><Link to="/" className="hover:underline">Home</Link></li>
          <li><Link to="/about" className="hover:underline">About</Link></li>
          <li><Link to="/contact" className="hover:underline">Contact</Link></li>
          <li><Link to="/dashboard" className="hover:underline">Dashboard</Link></li>
          <li><Link to="/notice" className="hover:underline">Notice</Link></li>
          <li><Link to="/result" className="hover:underline">Result</Link></li>
        </ul>
      </nav>
    </header>
  );
};

export default Header;
