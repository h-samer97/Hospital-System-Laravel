import React, { useState, FormEvent } from "react";
import axios from "axios";
import { RoleKey, RoleConfig, LoginFormData } from "./types";

interface Props {
  role: RoleKey;
  config: RoleConfig;
}

const MultiLoginForm: React.FC<Props> = ({ role, config }) => {
  // Field state
  const [email, setEmail]       = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading]   = useState(false);
  const [error, setError]       = useState<string | null>(null);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault(); // Prevent page reload
    setLoading(true);
    setError(null);

    const data: LoginFormData = { email, password, role };

    try {
      // Send data to API
      const response = await axios.post(config.action, data);
      // After success, redirect user
      window.location.href = response.data.redirect ?? "/dashboard";
    } catch (err: unknown) {
      // Handle errors safely with TypeScript
      if (axios.isAxiosError(err)) {
        setError(err.response?.data?.message ?? "An error occurred, please try again.");
      } else {
        setError("An unexpected error occurred.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div id={role} className="mt-6">
      <h2 className="text-xl font-semibold mb-4">{config.label}</h2>

      {/* Display error message if exists */}
      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <ul className="list-disc list-inside"><li>{error}</li></ul>
        </div>
      )}

      {/*
        onSubmit on form instead of onClick on button
        This allows form submission by pressing Enter
      */}
      <form onSubmit={handleSubmit} action={config.action} method="post">

        <div className="mb-4">
          <label htmlFor={`email-${role}`} className="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            id={`email-${role}`}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            type="email"
            name="email"
            placeholder="Enter your email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            autoFocus
            autoComplete="email"
          />
        </div>

        <div className="mb-4">
          <label htmlFor={`pass-${role}`} className="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input
            id={`pass-${role}`}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            type="password"
            name="password"
            placeholder="Enter your password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            autoComplete="current-password"
          />
        </div>

        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          disabled={loading}
        >
          {loading ? "Verifying..." : "Login"}
        </button>
      </form>

      <div className="mt-5 text-sm">
        <p className="mb-2"><a href="/forgot-password" className="text-blue-600 hover:underline">Forgot password?</a></p>
        <p>
          Don't have an account?{" "}
          <a href="/signup" className="text-blue-600 hover:underline">Create new account</a>
        </p>
      </div>
    </div>
  );
};

export default MultiLoginForm;