import React, { useEffect, useRef } from "react";
import Form from "./components/Form";
import { StoreContext } from "./store/useStore";
import useDataStore from "./store/useDataStore";

const App = () => {
    const ref = useRef(null);

    const handleClickOutside = (event) => {
        if (ref.current && !ref.current.contains(event.target)) {
            document.getElementById("jlt-power-user-root-custom").style.display = "none";
        }
    };

    useEffect(() => {
        document.addEventListener("click", handleClickOutside, true);
        return () => {
            document.removeEventListener("click", handleClickOutside, true);
        };
    }, []);

    return (
        <StoreContext.Provider value={useDataStore()}>
            <div className="jltpu_search-form-wrapper">
                <div className="jltpu_search-form-overlay">
                    <div ref={ref}>
                        <Form />
                    </div>
                </div>

                {/* <div className="jltpu_settings-overlay">
                    <h2>Settings</h2>
                </div> */}
            </div>
        </StoreContext.Provider>
    );
};

export default App;
