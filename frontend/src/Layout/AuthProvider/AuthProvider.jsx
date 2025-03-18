import { createUserWithEmailAndPassword, getAuth, onAuthStateChanged, signInWithEmailAndPassword, signInWithPopup, signOut, updateProfile } from 'firebase/auth';
import PropTypes from 'prop-types';
import { createContext, useEffect, useState } from "react";
import app from '../../../firebase.config';
import useAxiosPublic from '../../hooks/useAxiosPublic';


export const AuthContext = createContext(null);

const auth = getAuth(app);

const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const axiosPublic = useAxiosPublic();


    const signInWithPopup = (auth, githubProvider) => {
        setLoading(true);
        return signInWithPopup(auth, githubProvider);
    }

    // 




    const createUser = (email, password, name, photoUrl) => {
        setLoading(true);
        return createUserWithEmailAndPassword(auth, email, password, name, photoUrl);
    }


    // update
    const updateUserProfile = (name, photoUrl,role) => {
        return updateProfile(auth.currentUser, {
            displayName: name,
            photoURL: photoUrl,
            role:role
        }).then(() => {

        }).catch(() => {

        });
    }



    const signIn = (email, password) => {
        setLoading(true);
        return signInWithEmailAndPassword(auth, email, password);
    }

    const logOut = () => {
        setLoading(true);
        return signOut(auth);
    }


    useEffect(() => {
        const unSubscribe = onAuthStateChanged(auth, currentUser => {
            setUser(currentUser);




            if (currentUser) {
                const loggedUser = { email: currentUser?.email }

                axiosPublic.post('/jwt', loggedUser)
                    .then(res => {
                        if (res.data.token) {
                            localStorage.setItem('access-token', res.data.token);
                            setLoading(false);
                        }
                    })
            }
            else {
                localStorage.removeItem('access-token');
                setLoading(false);
            }
            

        });
        return () => {
            unSubscribe();
        }
    }, [axiosPublic])



    const authInfo = {
        user,

        signInWithPopup,
        setUser,
        loading,
        createUser,
        signIn,
        logOut,
        updateUserProfile
    };
    return (
        <AuthContext.Provider value={authInfo}>
            {children}
        </AuthContext.Provider>
    );
};
AuthProvider.propTypes = {
    children: PropTypes.node,
}


export default AuthProvider;