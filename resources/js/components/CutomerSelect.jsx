import React, { useState, useEffect } from "react";
import CreatableSelect from "react-select/creatable";
import axios from "axios";

const CustomerSelect = ({ setCottageId }) => {
    const [customers, setCustomers] = useState([]);
    const [selectedCustomer, setSelectedCustomer] = useState({value:1,label:"Select Cottage"});

    // Fetch existing customers from the backend
    useEffect(() => {
      axios.get("/admin/get/cottages").then((response) => {
            const customerOptions = response?.data?.map((customer) => ({
                value: customer.id,
                label: customer.name,
            }));
            setCustomers(customerOptions);
        });
    }, []);
  useEffect(() => {
    setCottageId(selectedCustomer?.value);
  }, [selectedCustomer]);

    const handleCreateCustomer = (inputValue) => {
        axios
            .post("/admin/create/cottages", { name: inputValue })
            .then((response) => {
                const newCustomer = response.data;
                const newOption = {
                    value: newCustomer.id,
                    label: newCustomer.name,
                };
                setCustomers((prev) => [newOption,...prev]);
                setSelectedCustomer(newOption);
            })
            .catch((error) => {
                console.error("Error creating customer:", error);
            });
    };

    const handleChange = (newValue) => {
        setSelectedCustomer(newValue);
    };

    return (
        <CreatableSelect
            isClearable
            options={customers}
            onChange={handleChange}
            onCreateOption={handleCreateCustomer} // Handle creating a new customer
            value={selectedCustomer}
            placeholder="Select cottage or create new"
        />
    );
};

export default CustomerSelect;
