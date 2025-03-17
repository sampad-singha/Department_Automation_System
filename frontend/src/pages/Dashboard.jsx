import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const Dashboard = () => {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);

  // Retrieve user data from localStorage
  useEffect(() => {
    const userData = JSON.parse(localStorage.getItem('user'));

    // If no user data exists, redirect to login
    if (!userData) {
      navigate('/login');
    } else {
      setUser(userData);
    }
  }, [navigate]);

  // Handle logout
  const handleLogout = () => {
    localStorage.removeItem('token'); // Remove token
    localStorage.removeItem('user'); // Remove user data
    navigate('/login'); // Redirect to login page
  };

  // If user is null (still loading), show loading message
  if (!user) {
    return <div className="text-xl text-center">Loading...</div>;
  }

  return (
    <div className="min-h-screen p-8 bg-gray-100">
      {/* Header Section */}
      <div className="flex items-center justify-between mb-8">
        <h1 className="text-3xl font-semibold text-gray-900">
          Welcome to your Dashboard, {user.name}!
        </h1>
        <button
          onClick={handleLogout}
          className="px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        >
          Logout
        </button>
      </div>

      {/* User Profile Section */}
      <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div className="p-6 bg-white rounded-lg shadow-lg">
          <h2 className="mb-4 text-2xl font-medium">Profile Information</h2>
          <div className="flex items-center space-x-6">
            <img
              src={user.image}
              alt={user.name}
              className="object-cover w-32 h-32 rounded-full"
            />
            <div>
              <p className="text-gray-600">Name: {user.name}</p>
              <p className="text-gray-600">Email: {user.email}</p>
              <p className="text-gray-600">Phone: {user.phone}</p>
              <p className="text-gray-600">Designation: {user.designation}</p>
              <p className="text-gray-600">City: {user.city}</p>
              <p className="text-gray-600">Address: {user.address}</p>
            </div>
          </div>
        </div>

        {/* User Academic Information Section */}
        <div className="p-6 bg-white rounded-lg shadow-lg">
          <h3 className="mb-4 text-2xl font-medium">Academic Information</h3>
          <div className="space-y-2">
            <p className="text-gray-600">University ID: {user.university_id}</p>
            <p className="text-gray-600">Department ID: {user.department_id}</p>
            <p className="text-gray-600">Session: {user.session}</p>
            <p className="text-gray-600">Year: {user.year}</p>
            <p className="text-gray-600">Semester: {user.semester}</p>
          </div>
        </div>
      </div>

      {/* Additional Information Section */}
      <div className="p-6 mt-8 bg-white rounded-lg shadow-lg">
        <h3 className="mb-4 text-2xl font-medium">Additional Information</h3>
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <p className="text-gray-600">Date of Birth: {user.dob}</p>
            <p className="text-gray-600">Status: {user.status}</p>
            <p className="text-gray-600">Publication Count: {user.publication_count}</p>
          </div>
          <div>
            <p className="text-gray-600">Email Verified At: {user.email_verified_at}</p>
            <p className="text-gray-600">Created At: {user.created_at}</p>
            <p className="text-gray-600">Updated At: {user.updated_at}</p>
          </div>
        </div>
      </div>

      
    </div>
  );
};

export default Dashboard;