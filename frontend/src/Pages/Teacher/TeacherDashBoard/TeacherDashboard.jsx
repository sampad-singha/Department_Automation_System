import { useAuth } from "../../../Contexts/AuthContext.jsx";
import {
    UserIcon,
    EnvelopeIcon,
    CalendarIcon,
    PhoneIcon,
    AcademicCapIcon,
    BuildingLibraryIcon,
    IdentificationIcon,
} from "@heroicons/react/24/outline";
import Dashboard from "../../../Component/Dashboard.jsx";
import CollapsibleSection from "../../../Component/CollapsibleSection.jsx";

export default function TeacherDashboard() {
    const { user } = useAuth();

    return (
        <div className="flex justify-center items-center min-h-screen bg-gray-900"> {/* Center content */}
            <div className="shadow-lg rounded-lg w-full max-w-4xl p-6"> {/* Increased width */}
                <Dashboard user={user}>
                    <CollapsibleSection title="Profile Information">
                        <DetailItem icon={EnvelopeIcon} label="Email" value={user?.email} />
                        <DetailItem icon={CalendarIcon} label="Date of Birth" value={user?.dob} />
                        <DetailItem icon={PhoneIcon} label="Phone" value={user?.phone} />
                        <DetailItem icon={IdentificationIcon} label="University ID" value={user?.university_id} />
                    </CollapsibleSection>

                    <CollapsibleSection title="Academic Information">
                        <DetailItem icon={AcademicCapIcon} label="Designation" value={user?.designation} />
                    </CollapsibleSection>

                    <CollapsibleSection title="Department Information">
                        <DetailItem icon={BuildingLibraryIcon} label="Department" value={user?.department?.name} />
                        <DetailItem icon={UserIcon} label="Faculty" value={user?.department?.faculty} />
                        <DetailItem icon={IdentificationIcon} label="Short Name" value={user?.department?.short_name} />
                    </CollapsibleSection>
                </Dashboard>
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
