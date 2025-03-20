import React from "react";
import { useAuth } from "../../../Contexts/AuthContext.jsx";
import {
    UserIcon,
    EnvelopeIcon,
    CalendarIcon,
    PhoneIcon,
    AcademicCapIcon,
    BuildingLibraryIcon,
    IdentificationIcon,
    BuildingOfficeIcon,
    ArrowRightStartOnRectangleIcon
} from "@heroicons/react/24/outline";

export default function TeacherDashboard() {
    const { user, logout } = useAuth();

    return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white px-4">
            <div className="bg-gray-800 shadow-lg rounded-lg p-6 w-full max-w-2xl border border-gray-700">

                {/* Profile Section */}
                <div className="flex items-center space-x-4 border-b border-gray-700 pb-4">
                    <img
                        src={user?.image || "https://via.placeholder.com/150"}
                        alt="Profile"
                        className="w-20 h-20 rounded-full border border-gray-600"
                    />
                    <div>
                        <h2 className="text-2xl font-semibold text-white flex items-center gap-2">
                            <UserIcon className="h-6 w-6 text-gray-400" />
                            {user?.name}
                        </h2>
                        <p className="text-gray-400 flex items-center gap-2">
                            <AcademicCapIcon className="h-5 w-5 text-gray-400" />
                            {user?.designation}
                        </p>
                    </div>
                </div>

                {/* Details Section */}
                <div className="mt-4 space-y-3">
                    <DetailItem icon={EnvelopeIcon} label="Email" value={user?.email} />
                    <DetailItem icon={CalendarIcon} label="Date of Birth" value={user?.dob} />
                    <DetailItem icon={PhoneIcon} label="Phone" value={user?.phone} />
                    <DetailItem icon={IdentificationIcon} label="University ID" value={user?.university_id} />

                    {/* Department Section */}
                    <div className="mt-6 border-t border-gray-700 pt-4">
                        <h3 className="text-xl font-semibold flex items-center gap-2 text-white">
                            <BuildingOfficeIcon className="h-6 w-6 text-gray-400" />
                            Department Information
                        </h3>
                        <DetailItem icon={BuildingLibraryIcon} label="Department" value={user?.department?.name} />
                        <DetailItem icon={UserIcon} label="Faculty" value={user?.department?.faculty} />
                        <DetailItem icon={IdentificationIcon} label="Short Name" value={user?.department?.short_name} />
                    </div>
                </div>

                {/* Logout Button */}
                <div className="mt-6 flex justify-center">
                    <button
                        type="button"
                        onClick={logout}
                        className="px-5 py-2 flex items-center gap-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition"
                    >
                        <ArrowRightStartOnRectangleIcon className="h-5 w-5" />
                        Logout
                    </button>
                </div>
            </div>
        </div>
    );
}

// Component for displaying details with an icon
const DetailItem = ({ icon: Icon, label, value }) => (
    <div className="flex items-center space-x-3 text-gray-300">
        <Icon className="h-5 w-5 text-gray-400" />
        <p className="font-medium">{label}:</p>
        <p className="text-white">{value || "N/A"}</p>
    </div>
);
