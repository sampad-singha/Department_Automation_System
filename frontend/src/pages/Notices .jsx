import { useEffect, useState } from 'react';
import axios from 'axios';
import { Outlet, useNavigate } from 'react-router-dom';

const Notices = () => {
  const [notices, setNotices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [department, setDepartment] = useState('');
  const [days, setDays] = useState('');
  const navigate = useNavigate();

  // Fetch notices based on filters
  const fetchNotices = async () => {
    setLoading(true);
    setError(null);

    try {
      const params = {};
      if (department) params.department = department;
      if (days) params.days = days;

      const url = `http://127.0.0.1:8000/api/show-notice?${new URLSearchParams(params).toString()}`;
      const response = await axios.get(url);

      setNotices(response.data.notices || []);
    } catch (err) {
      setError('Error fetching notices');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchNotices();
  }, [department, days]);

  // Handle click on a notice
  const handleNoticeClick = (id) => {
    navigate(`/notice/${id}`); // Navigate to the detailed notice view
  };

  return (
    <div className="min-h-screen p-8 bg-gray-50">
      <div className="max-w-4xl p-6 mx-auto space-y-8 bg-white rounded-lg shadow-lg">
        <h1 className="mb-6 text-4xl font-semibold text-gray-800">Notices</h1>

        {/* Filter Controls */}
        <div className="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
          <div>
            <label htmlFor={department} className="block text-sm font-medium text-gray-700">Department</label>
            <input
                id={department}
              type="text"
              value={department}
              onChange={(e) => setDepartment(e.target.value)}
              placeholder="Enter department (e.g., CSE)"
              className="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label htmlFor={days} className="block text-sm font-medium text-gray-700">Days</label>
            <input
                id={days}
              type="number"
              value={days}
              onChange={(e) => setDays(e.target.value)}
              placeholder="Enter days (e.g., 30)"
              className="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        {/* Loading and Error Messages */}
        {loading && (
          <div className="text-xl text-center text-gray-600">
            <div className="w-10 h-10 mx-auto mb-4 border-4 border-t-4 border-blue-500 rounded-full animate-spin"></div>
            Loading...
          </div>
        )}
        {error && (
          <div className="text-center text-red-500">
            <p>{error}</p>
          </div>
        )}

        {/* Display Notices */}
        {!loading && !error && (
          <div className="space-y-6">
            {notices.length > 0 ? (
              notices.map((notice) => (
                  <button
                      key={notice.id}
                      onClick={() => handleNoticeClick(notice.id)}
                      className="w-full text-left p-6 transition-shadow duration-300 ease-in-out bg-white border border-gray-200 rounded-lg shadow-md cursor-pointer hover:shadow-lg"
                  >
                    <h2 className="text-2xl font-medium text-gray-800">{notice.title}</h2>
                    <p className="mt-1 text-sm text-gray-500">Posted on: {notice.published_on}</p>
                  </button>

              ))
            ) : (
              <div className="text-lg text-center text-gray-500">No notices found for the specified filter.</div>
            )}
          </div>
        )}

        {/* Render nested route (NoticeDetail) */}
        <Outlet />
      </div>
    </div>
  );
};

export default Notices;