import { useState } from 'react';
import axios from 'axios';

const Result = () => {
  // State to store user inputs and fetched results
  const [year, setYear] = useState('');
  const [semester, setSemester] = useState('');
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Fetch results based on year and semester
  const fetchResults = async () => {
    if (!year || !semester) {
      setError('Both year and semester are required.');
      return;
    }

    setLoading(true);
    setError(null);
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/result/show-full/${year}/${semester}`);
      setResults(response.data); // Assuming the result is returned as an array
    } catch (err) {
      setError('Failed to fetch results. Please try again.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen p-8 bg-gray-50">
      <div className="max-w-4xl p-6 mx-auto space-y-8 bg-white rounded-lg shadow-lg">
        <h1 className="mb-6 text-4xl font-semibold text-gray-800">Student Results</h1>

        {/* Input Form */}
        <div className="space-y-4">
          <div>
            <label htmlFor={year} className="block text-sm font-medium text-gray-700">Year</label>
            <input
                id={year}
              type="number"
              value={year}
              onChange={(e) => setYear(e.target.value)}
              placeholder="Enter year (e.g., 2025)"
              className="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label htmlFor={semester} className="block text-sm font-medium text-gray-700">Semester</label>
            <input
                id={semester}
              type="number"
              value={semester}
              onChange={(e) => setSemester(e.target.value)}
              placeholder="Enter semester (e.g., 1)"
              className="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <button
              onClick={fetchResults}
              className="w-full py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Fetch Results
            </button>
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

        {/* Display Results */}
        {!loading && !error && results.length > 0 && (
            <div className="space-y-6">
              {results.map((result) => (
                  <div key={result.id} className="p-6 transition-shadow duration-300 ease-in-out bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg">
                    <h2 className="text-2xl font-medium text-gray-800">{result.subject_name}</h2>
                    <p className="mt-1 text-sm text-gray-500">Code: {result.subject_code}</p>
                    <p className="mt-3 text-gray-600">Grade: {result.grade}</p>
                  </div>
              ))}
            </div>
        )}

        {/* No results found message */}
        {!loading && !error && results.length === 0 && (
          <div className="text-lg text-center text-gray-500">No results found for the given year and semester.</div>
        )}
      </div>
    </div>
  );
};

export default Result;
