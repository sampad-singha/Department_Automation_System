import { useState, useContext } from "react"
import { useNavigate, Link } from "react-router-dom"
import { toast, ToastContainer } from "react-toastify"
import "react-toastify/dist/ReactToastify.css"
import { AuthContext } from "../../../Layout/AuthProvider/AuthProvider"

const SignUp = () => {
  const [name, setName] = useState("")
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [role, setRole] = useState("user")
  const [image, setImage] = useState(null)
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()
  const authContext = useContext(AuthContext)

  if (!authContext) {
    throw new Error("SignUp must be used within an AuthProvider")
  }

  const { createUser, updateUserProfile } = authContext

  // Function to handle sign up form submission
  const handleSignUp = async (e) => {
    e.preventDefault()
    setLoading(true)

    try {
      // Create user with email and password
      const user = await createUser(email, password, name)

      // If an image is selected, upload it
      if (image) {
        const formData = new FormData()
        formData.append("image", image)
        const response = await fetch("/api/upload-image", {
          method: "POST",
          body: formData,
        })
        if (response.ok) {
          const { imageUrl } = await response.json()
          // Update user profile with name, image URL, and role
          await updateUserProfile(name, imageUrl, role)
        }
      } else {
        // Update user profile with name and role (no image)
        await updateUserProfile(name, undefined, role)
      }

      toast.success("Successfully signed up")
      // Redirect to login page after successful sign up
      navigate("/login")
    } catch (error) {
      toast.error("Failed to sign up")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 className="text-2xl font-bold mb-6 text-center">Sign Up</h2>
        <form onSubmit={handleSignUp}>
          {/* Name input field */}
          <div className="mb-4">
            <label htmlFor="name" className="block text-gray-700 text-sm font-bold mb-2">
              Name
            </label>
            <input
              type="text"
              id="name"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
            />
          </div>
          {/* Email input field */}
          <div className="mb-4">
            <label htmlFor="email" className="block text-gray-700 text-sm font-bold mb-2">
              Email
            </label>
            <input
              type="email"
              id="email"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          {/* Password input field */}
          <div className="mb-4">
            <label htmlFor="password" className="block text-gray-700 text-sm font-bold mb-2">
              Password
            </label>
            <input
              type="password"
              id="password"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          
          {/* Image upload field */}
          <div className="mb-6">
            <label htmlFor="image" className="block text-gray-700 text-sm font-bold mb-2">
              Profile Image
            </label>
            <input
              type="file"
              id="image"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              onChange={(e) => setImage(e.target.files ? e.target.files[0] : null)}
              accept="image/*"
            />
          </div>
          {/* Submit button */}
          <button
            type="submit"
            className="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
            disabled={loading}
          >
            {loading ? "Signing up..." : "Sign Up"}
          </button>
        </form>
        {/* Link to login page */}
        <p className="mt-4 text-center">
          Already have an account?{" "}
          <Link to="/login" className="text-blue-500 hover:underline">
            Login
          </Link>
        </p>
      </div>
      {/* Toast notifications container */}
      <ToastContainer />
    </div>
  )
}

export default SignUp

